<?php

namespace ItDevgroup\LaravelBannerApi\Handler;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Event\BannerApiPositionListBuilderEvent;
use ItDevgroup\LaravelBannerApi\Model\BannerPositionFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerPositionModel;

/**
 * Class BannerPositionHandler
 * @package ItDevgroup\LaravelBannerApi\Handler
 */
class BannerPositionHandler
{
    /**
     * @var string|null
     */
    private ?string $model;
    /**
     * @var string
     */
    private string $operatorLike;

    /**
     * BannerPositionHandler constructor.
     */
    public function __construct()
    {
        $this->model = Config::get('banner_api.model.position');
        $this->operatorLike = Config::get('banner_api.query.operator.like');
    }

    /**
     * @param BannerPositionFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LengthAwarePaginator|Builder|Collection|BannerPositionModel[]
     */
    public function list(
        ?BannerPositionFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
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
            new BannerApiPositionListBuilderEvent(
                $builder,
                $filter,
                $option
            )
        );

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
     * @param int $id
     * @return BannerPositionModel
     */
    public function byId(int $id): Model
    {
        $builder = $this->getBuilder();
        $builder->where('id', '=', $id);

        return $builder->firstOrFail();
    }

    /**
     * @param BannerPositionModel $model
     * @return bool
     */
    public function save(BannerPositionModel $model): bool
    {
        return $model->save();
    }

    /**
     * @param BannerPositionModel $model
     * @return bool
     */
    public function delete(BannerPositionModel $model): bool
    {
        return $model->delete();
    }

    /**
     * @param string $title
     * @return BannerPositionModel
     */
    public function newModel(
        string $title
    ): BannerPositionModel {
        /** @var BannerPositionModel $model */
        $modelName = $this->model;
        $model = new $modelName();
        $model->title = $title;

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
     * @param Builder $builder
     * @param BannerPositionFilter|null $filter
     */
    protected function filter(Builder $builder, ?BannerPositionFilter $filter = null): void
    {
        if (!$filter) {
            return;
        }

        if ($filter->getTitle()) {
            $builder->where(
                'title',
                $this->operatorLike,
                sprintf('%%%s%%', $filter->getTitle())
            );
        }
        if ($filter->getGroupName()) {
            $builder->where(
                $builder->raw('lower(group_name)'),
                '=',
                Str::lower($filter->getGroupName())
            );
        }
    }
}
