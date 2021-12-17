<?php

namespace ItDevgroup\LaravelBannerApi\Event;

use Illuminate\Database\Eloquent\Builder;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Model\BannerPositionFilter;

/**
 * Class BannerApiPositionListBuilderEvent
 * @package ItDevgroup\LaravelBannerApi\Event
 */
class BannerApiPositionListBuilderEvent
{
    /**
     * @var Builder
     */
    private Builder $builder;
    /**
     * @var BannerPositionFilter|null
     */
    private ?BannerPositionFilter $filter;
    /**
     * @var BannerRequestOption|null
     */
    private ?BannerRequestOption $option;

    /**
     * BannerApiPositionListBuilderEvent constructor.
     * @param Builder $builder
     * @param BannerPositionFilter|null $filter
     * @param BannerRequestOption|null $option
     */
    public function __construct(
        Builder $builder,
        ?BannerPositionFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $this->builder = $builder;
        $this->filter = $filter;
        $this->option = $option;
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @return BannerPositionFilter|null
     */
    public function getFilter(): ?BannerPositionFilter
    {
        return $this->filter;
    }

    /**
     * @return BannerRequestOption|null
     */
    public function getOption(): ?BannerRequestOption
    {
        return $this->option;
    }
}
