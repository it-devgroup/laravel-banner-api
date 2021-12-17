<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;

/**
 * Class BannerStatisticClickFilter
 * @package ItDevgroup\LaravelBannerApi\Model
 */
class BannerStatisticClickFilter
{
    /**
     * @var int|null
     */
    protected ?int $banner = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $createdAtFrom = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $createdAtTo = null;

    /**
     * @return int|null
     */
    public function getBanner(): ?int
    {
        return $this->banner;
    }

    /**
     * @param int|null $banner
     * @return BannerStatisticClickFilter
     */
    public function setBanner(?int $banner): BannerStatisticClickFilter
    {
        $this->banner = $banner;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAtFrom(): ?Carbon
    {
        return $this->createdAtFrom;
    }

    /**
     * @param Carbon|null $createdAtFrom
     * @return BannerStatisticClickFilter
     */
    public function setCreatedAtFrom(?Carbon $createdAtFrom): BannerStatisticClickFilter
    {
        $this->createdAtFrom = $createdAtFrom;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAtTo(): ?Carbon
    {
        return $this->createdAtTo;
    }

    /**
     * @param Carbon|null $createdAtTo
     * @return BannerStatisticClickFilter
     */
    public function setCreatedAtTo(?Carbon $createdAtTo): BannerStatisticClickFilter
    {
        $this->createdAtTo = $createdAtTo;
        return $this;
    }
}
