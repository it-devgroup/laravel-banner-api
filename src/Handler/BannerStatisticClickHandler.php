<?php

namespace ItDevgroup\LaravelBannerApi\Handler;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\LazyCollection;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\BannerServiceInterface;
use ItDevgroup\LaravelBannerApi\Event\BannerApiStatisticClickListBuilderEvent;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;
use ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel;

/**
 * Class BannerStatisticClickHandler
 * @package ItDevgroup\LaravelBannerApi\Handler
 */
class BannerStatisticClickHandler
{
    /**
     * @var string|null
     */
    private ?string $model;
    /**
     * @var array
     */
    private array $bannerFields;
    /**
     * @var string|null
     */
    private ?string $saveMode;

    /**
     * BannerStatisticClickHandler constructor.
     */
    public function __construct()
    {
        $this->model = Config::get('banner_api.model.statistic_click');
        $this->bannerFields = Config::get('banner_api.statistic_click.banner_fields');
        $this->saveMode = Config::get('banner_api.statistic_click.save_mode');
    }

    /**
     * @param BannerStatisticClickFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LengthAwarePaginator|Builder|Collection|BannerStatisticClickModel[]
     */
    public function list(
        ?BannerStatisticClickFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $builder = $this->listBuilder($filter, $option);

        if ($option && $option->getPage() && $option->getPerPage()) {
            return $builder->paginate(
                $option->getPerPage(),
                ['*'],
                'page',
                $option->getPage()
            );
        }

        return $builder->get();
    }

    /**
     * @param BannerStatisticClickFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LazyCollection|BannerStatisticClickModel[]
     */
    public function listByCursor(
        ?BannerStatisticClickFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $builder = $this->listBuilder($filter, $option);

        return $builder->cursor();
    }

    /**
     * @param int $id
     * @return BannerStatisticClickModel|Model
     */
    public function byId(int $id): Model
    {
        $builder = $this->getBuilder();
        $builder->where('id', '=', $id);

        return $builder->firstOrFail();
    }

    /**
     * @param BannerStatisticClickModel $model
     * @return bool
     */
    public function save(BannerStatisticClickModel $model): bool
    {
        $this->saveModelProperties($model);

        $model = $this->modelModify($model);

        return $model->save();
    }

    /**
     * @param BannerStatisticClickModel $model
     * @return bool
     */
    public function delete(BannerStatisticClickModel $model): bool
    {
        return $model->delete();
    }

    /**
     * @param BannerModel $banner
     * @param string|null $pageReferrer
     * @param array $properties
     * @return BannerStatisticClickModel
     */
    public function newModel(
        BannerModel $banner,
        ?string $pageReferrer = null,
        array $properties = []
    ): BannerStatisticClickModel {
        /** @var BannerStatisticClickModel $model */
        $modelName = $this->model;
        $model = new $modelName();
        $model->page_referrer = $pageReferrer;
        $model->properties = $properties;
        $model->banner()->associate($banner);

        return $model;
    }

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->model::query();
    }

    /**
     * @param BannerStatisticClickFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return Builder
     */
    protected function listBuilder(
        ?BannerStatisticClickFilter $filter = null,
        ?BannerRequestOption $option = null
    ): builder {
        $builder = $this->getBuilder();

        if ($filter && (!$option || !$option->isDisableFilter())) {
            $this->filter($builder, $filter);
        }

        if ($option && !$option->isDisableSort()) {
            $builder->orderBy(
                $option->getSortField(),
                $option->getSortDirection()
            );
        }

        Event::dispatch(
            new BannerApiStatisticClickListBuilderEvent(
                $builder,
                $filter,
                $option
            )
        );

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param BannerStatisticClickFilter|null $filter
     */
    protected function filter(Builder $builder, ?BannerStatisticClickFilter $filter = null): void
    {
        if (!$filter) {
            return;
        }

        if ($filter->getBanner()) {
            $builder->where(
                'banner_id',
                '=',
                $filter->getBanner()
            );
        }
        if ($filter->getCreatedAtFrom()) {
            $builder->where(
                'created_at',
                '>=',
                $filter->getCreatedAtFrom()->toDateTimeString()
            );
        }
        if ($filter->getCreatedAtTo()) {
            $builder->where(
                'created_at',
                '<=',
                $filter->getCreatedAtTo()->toDateTimeString()
            );
        }
    }

    /**
     * @param BannerStatisticClickModel $model
     */
    protected function saveModelProperties(BannerStatisticClickModel $model): void
    {
        $data = [];

        foreach ($this->bannerFields as $field) {
            $fieldName = sprintf(
                'banner_%s',
                $field
            );
            $data[$fieldName] = $model->banner->$field;
        }

        $model->properties = array_merge($model->properties, $data);
    }

    /**
     * @param BannerStatisticClickModel $model
     * @return BannerStatisticClickModel|Model
     */
    protected function modelModify(BannerStatisticClickModel $model): Model
    {
        $builder = $this->getBuilder();

        $builder->where('banner_id', '=', $model->banner_id);
        $builder->where('page_referrer', '=', $model->page_referrer);

        $createdAtFrom = Carbon::now();
        $createdAtTo = Carbon::now();

        switch ($this->saveMode) {
            case BannerServiceInterface::STATISTIC_CLICK_SAVE_MODE_MINUTE:
                $createdAtFrom->startOfMinute();
                $createdAtTo->endOfMinute();
                break;
            case BannerServiceInterface::STATISTIC_CLICK_SAVE_MODE_HOUR:
                $createdAtFrom->startOfHour();
                $createdAtTo->endOfHour();
                break;
            case BannerServiceInterface::STATISTIC_CLICK_SAVE_MODE_DAY:
                $createdAtFrom->startOfDay();
                $createdAtTo->endOfDay();
                break;
            default:
                return $model;
        }
        $builder->where(
            'created_at',
            '>=',
            $createdAtFrom->toDateTimeString()
        );
        $builder->where(
            'created_at',
            '<=',
            $createdAtTo->toDateTimeString()
        );

        /** @var BannerStatisticClickModel $existsModel */
        $existsModel = $builder->first();

        if (!$existsModel) {
            return $model;
        }

        $clickCount = $existsModel->click_count;

        foreach ($model->attributesToArray() as $field => $value) {
            $existsModel->$field = $value;
        }

        $existsModel->click_count = $clickCount + 1;

        unset($model);

        return $existsModel;
    }
}
