<?php

namespace ItDevgroup\LaravelBannerApi\Event;

use Illuminate\Database\Eloquent\Builder;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Model\BannerImageFilter;

/**
 * Class BannerApiImageListBuilderEvent
 * @package ItDevgroup\LaravelBannerApi\Event
 */
class BannerApiImageListBuilderEvent
{
    /**
     * @var Builder
     */
    private Builder $builder;
    /**
     * @var BannerImageFilter|null
     */
    private ?BannerImageFilter $filter;
    /**
     * @var BannerRequestOption|null
     */
    private ?BannerRequestOption $option;

    /**
     * BannerApiImageListBuilderEvent constructor.
     * @param Builder $builder
     * @param BannerImageFilter|null $filter
     * @param BannerRequestOption|null $option
     */
    public function __construct(
        Builder $builder,
        ?BannerImageFilter $filter = null,
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
     * @return BannerImageFilter|null
     */
    public function getFilter(): ?BannerImageFilter
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
