<?php

namespace ItDevgroup\LaravelBannerApi;

use ItDevgroup\LaravelBannerApi\Handler\BannerAttachModelHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerImageHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerPositionHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerStatisticClickHandler;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;

/**
 * Interface BannerServiceInterface
 * @package ItDevgroup\LaravelBannerApi
 */
interface BannerServiceInterface
{
    /**
     * @type int
     */
    public const STATISTIC_CLICK_SAVE_MODE_DEFAULT = 1;
    /**
     * @type int
     */
    public const STATISTIC_CLICK_SAVE_MODE_MINUTE = 2;
    /**
     * @type int
     */
    public const STATISTIC_CLICK_SAVE_MODE_HOUR = 3;
    /**
     * @type int
     */
    public const STATISTIC_CLICK_SAVE_MODE_DAY = 4;

    /**
     * @return BannerPositionHandler
     */
    public function getBannerPositionHandler(): BannerPositionHandler;

    /**
     * @return BannerHandler
     */
    public function getBannerHandler(): BannerHandler;

    /**
     * @return BannerImageHandler
     */
    public function getBannerImageHandler(): BannerImageHandler;

    /**
     * @return BannerStatisticClickHandler
     */
    public function getBannerStatisticClickHandler(): BannerStatisticClickHandler;

    /**
     * @return BannerAttachModelHandler
     */
    public function getBannerAttachModelHandler(): BannerAttachModelHandler;

    /**
     * @param BannerModel $bannerModel
     */
    public function deleteBanner(BannerModel $bannerModel): void;

    /**
     * @param BannerModel $bannerModel
     */
    public function showBanner(BannerModel $bannerModel): void;

    /**
     * @param BannerModel $bannerModel
     * @param string|null $pageReferrer
     * @param array $properties
     */
    public function clickBanner(
        BannerModel $bannerModel,
        ?string $pageReferrer = null,
        array $properties = []
    ): void;
}
