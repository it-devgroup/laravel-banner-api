<?php

namespace ItDevgroup\LaravelBannerApi\Handler;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\LazyCollection;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Event\BannerApiBannerListBuilderEvent;
use ItDevgroup\LaravelBannerApi\Model\BannerAttachModel;
use ItDevgroup\LaravelBannerApi\Model\BannerFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;
use ItDevgroup\LaravelBannerApi\Model\BannerPositionModel;

/**
 * Class BannerHandler
 * @package ItDevgroup\LaravelBannerApi\Handler
 */
class BannerHandler
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
     * BannerHandler constructor.
     */
    public function __construct()
    {
        $this->model = Config::get('banner_api.model.banner');
        $this->operatorLike = Config::get('banner_api.query.operator.like');
    }

    /**
     * @param BannerFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LengthAwarePaginator|Builder|Collection|BannerModel[]
     */
    public function list(
        ?BannerFilter $filter = null,
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
     * @param BannerFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LazyCollection|BannerModel[]
     */
    public function listByCursor(
        ?BannerFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $builder = $this->listBuilder($filter, $option);

        return $builder->cursor();
    }

    /**
     * @param int $id
     * @return Model|BannerModel
     */
    public function byId(int $id): BannerModel
    {
        $builder = $this->getBuilder();
        $builder->where('id', '=', $id);

        return $builder->firstOrFail();
    }

    /**
     * @param BannerModel $model
     * @param array|SupportCollection|LengthAwarePaginator|BannerPositionModel[] $positions
     * @return bool
     */
    public function save(
        BannerModel $model,
        $positions = null
    ): bool {
        if (!$model->rank) {
            $model->rank = $this->nextRank();
        }

        $return = $model->save();

        $positions = $this->getPositionIds($positions);
        $model->positions()->sync($positions);

        return $return;
    }

    /**
     * @param BannerModel $model
     * @return bool
     */
    public function delete(BannerModel $model): bool
    {
        return $model->delete();
    }

    /**
     * @param string $title
     * @return BannerModel|Model
     */
    public function newModel(string $title): Model
    {
        /** @var BannerModel $model */
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
     * @param BannerFilter|null $filter
     */
    protected function filter(Builder $builder, ?BannerFilter $filter = null): void
    {
        if (!$filter) {
            return;
        }

        if (is_array($filter->getIds()) && !empty($filter->getIds())) {
            $builder->whereIn(
                'id',
                $filter->getIds()
            );
        }
        if ($filter->getTitle()) {
            $builder->where(
                'title',
                $this->operatorLike,
                sprintf('%%%s%%', $filter->getTitle())
            );
        }
        if (is_array($filter->getPositions()) && !empty($filter->getPositions())) {
            $builder->whereHas(
                'positions',
                function (Builder $builder) use ($filter) {
                    $builder->whereIn('position_id', $filter->getPositions());
                }
            );
        }
        if ($filter->isDiffTimeModeAlternative()) {
            $builder->where(
                function (Builder $builder) use ($filter) {
                    if ($filter->getStartAt()) {
                        $builder->where(
                            'start_at',
                            '>=',
                            $filter->getStartAt()->toDateTimeString()
                        );
                    }
                    if ($filter->getEndAt()) {
                        $builder->orWhere(
                            'end_at',
                            '<=',
                            $filter->getEndAt()->toDateTimeString()
                        );
                    }
                }
            );
        } else {
            if ($filter->getStartAt()) {
                $builder->where(
                    'start_at',
                    '<=',
                    $filter->getStartAt()->toDateTimeString()
                );
            }
            if ($filter->getEndAt()) {
                $builder->where(
                    'end_at',
                    '>=',
                    $filter->getEndAt()->toDateTimeString()
                );
            }
        }
        if (is_bool($filter->getIsActive())) {
            $builder->where(
                'is_active',
                '=',
                $filter->getIsActive()
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
     * @return int
     */
    protected function nextRank(): int
    {
        $builder = $this->getBuilder();

        return (int)$builder->max('rank') + 1;
    }

    /**
     * @param array|SupportCollection|LengthAwarePaginator|BannerPositionModel[] $positions
     * @return SupportCollection
     */
    protected function getPositionIds($positions = null): SupportCollection
    {
        $ids = collect();

        if ($positions instanceof LengthAwarePaginator) {
            $ids = collect($positions->items())->pluck('id');
        } elseif ($positions instanceof SupportCollection) {
            $ids = collect($positions)->pluck('id');
        } elseif (is_array($positions)) {
            $ids = collect($positions)->pluck('id');
        }

        if (!$ids->first()) {
            $ids = collect($positions);
        }

        return $ids;
    }

    /**
     * @param BannerFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return Builder
     */
    protected function listBuilder(
        ?BannerFilter $filter = null,
        ?BannerRequestOption $option = null
    ): Builder {
        $builder = $this->getBuilder();

        if ($filter && (!$option || !$option->isDisableFilter())) {
            $this->filter($builder, $filter);
        }

        if ($filter->getModelType() && $filter->getModelId()) {
            $builder->whereHas(
                'models',
                function (Builder $builder) use ($filter) {
                    $builder->whereNull('model_type', '=', $filter->getModelType());
                    $builder->whereNull('model_id', '=', $filter->getModelId());
                }
            );
        }

        if ($option && !$option->isDisableSort()) {
            if ($filter->getModelType() && $filter->getModelId() && $option->getSortField() == 'rank') {
                $subQueryFieldBannerId = sprintf(
                    '%s.id',
                    Config::get('banner_api.table.banner')
                );
                $subQuery = BannerAttachModel::query();
                $subQuery->select('rank');
                $subQuery->where('banner_id', '=', $subQuery->raw($subQueryFieldBannerId));
                $builder->select('*');
                $builder->selectSub($subQuery, 'modelRank');
                $option->setSortField('modelRank');
            }
            $builder->orderBy(
                $option->getSortField(),
                $option->getSortDirection()
            );
        }

        Event::dispatch(
            new BannerApiBannerListBuilderEvent(
                $builder,
                $filter,
                $option
            )
        );

        return $builder;
    }
}
