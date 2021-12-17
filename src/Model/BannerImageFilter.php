<?php

namespace ItDevgroup\LaravelBannerApi\Model;

/**
 * Class BannerImageFilter
 * @package ItDevgroup\LaravelBannerApi\Model
 */
class BannerImageFilter
{
    /**
     * @var int|null
     */
    protected ?int $banner = null;
    /**
     * @var string|null
     */
    protected ?string $type = null;
    /**
     * @var bool
     */
    protected bool $withoutBanner = false;

    /**
     * @return int|null
     */
    public function getBanner(): ?int
    {
        return $this->banner;
    }

    /**
     * @param int|null $banner
     * @return BannerImageFilter
     */
    public function setBanner(?int $banner): BannerImageFilter
    {
        $this->banner = $banner;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return BannerImageFilter
     */
    public function setType(?string $type): BannerImageFilter
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithoutBanner(): bool
    {
        return $this->withoutBanner;
    }

    /**
     * @param bool $withoutBanner
     * @return BannerImageFilter
     */
    public function setWithoutBanner(bool $withoutBanner): BannerImageFilter
    {
        $this->withoutBanner = $withoutBanner;
        return $this;
    }
}
