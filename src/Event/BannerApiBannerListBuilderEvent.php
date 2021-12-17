<?php

namespace ItDevgroup\LaravelBannerApi\Event;

use Illuminate\Database\Eloquent\Builder;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Model\BannerFilter;

/**
 * Class BannerApiBannerListBuilderEvent
 * @package ItDevgroup\LaravelBannerApi\Event
 */
class BannerApiBannerListBuilderEvent
{
    private Builder $builder;
    private ?BannerFilter $filter;
    private ?BannerRequestOption $option;

    /**
     * BannerApiBannerListBuilderEvent constructor.
     * @param Builder $builder
     * @param BannerFilter|null $filter
     * @param BannerRequestOption|null $option
     */
    public function __construct(
        Builder $builder,
        ?BannerFilter $filter = null,
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
     * @return BannerFilter|null
     */
    public function getFilter(): ?BannerFilter
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
