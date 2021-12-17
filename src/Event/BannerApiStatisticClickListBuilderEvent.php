<?php

namespace ItDevgroup\LaravelBannerApi\Event;

use Illuminate\Database\Eloquent\Builder;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickFilter;

/**
 * Class BannerApiStatisticClickListBuilderEvent
 * @package ItDevgroup\LaravelBannerApi\Event
 */
class BannerApiStatisticClickListBuilderEvent
{
    private Builder $builder;
    private ?BannerStatisticClickFilter $filter;
    private ?BannerRequestOption $option;

    /**
     * BannerApiStatisticClickListBuilderEvent constructor.
     * @param Builder $builder
     * @param BannerStatisticClickFilter|null $filter
     * @param BannerRequestOption|null $option
     */
    public function __construct(
        Builder $builder,
        ?BannerStatisticClickFilter $filter = null,
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
     * @return BannerStatisticClickFilter|null
     */
    public function getFilter(): ?BannerStatisticClickFilter
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
