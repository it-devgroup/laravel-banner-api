<?php

namespace ItDevgroup\LaravelBannerApi;

use ItDevgroup\LaravelBannerApi\Handler\BannerAttachModelHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerImageHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerPositionHandler;
use ItDevgroup\LaravelBannerApi\Handler\BannerStatisticClickHandler;
use ItDevgroup\LaravelBannerApi\Model\BannerImageFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;

/**
 * Class BannerService
 * @package ItDevgroup\LaravelBannerApi
 */
class BannerService implements BannerServiceInterface
{
    /**
     * @var BannerPositionHandler
     */
    private BannerPositionHandler $bannerPositionHandler;
    /**
     * @var BannerHandler
     */
    private BannerHandler $bannerHandler;
    /**
     * @var BannerImageHandler
     */
    private BannerImageHandler $bannerImageHandler;
    /**
     * @var BannerStatisticClickHandler
     */
    private BannerStatisticClickHandler $bannerStatisticClickHandler;
    /**
     * @var BannerAttachModelHandler
     */
    private BannerAttachModelHandler $bannerAttachModelHandler;

    /**
     * BannerService constructor.
     * @param BannerPositionHandler $bannerPositionHandler
     * @param BannerHandler $bannerHandler
     * @param BannerImageHandler $bannerImageHandler
     * @param BannerStatisticClickHandler $bannerStatisticClickHandler
     * @param BannerAttachModelHandler $bannerAttachModelHandler
     */
    public function __construct(
        BannerPositionHandler $bannerPositionHandler,
        BannerHandler $bannerHandler,
        BannerImageHandler $bannerImageHandler,
        BannerStatisticClickHandler $bannerStatisticClickHandler,
        BannerAttachModelHandler $bannerAttachModelHandler
    ) {
        $this->bannerPositionHandler = $bannerPositionHandler;
        $this->bannerHandler = $bannerHandler;
        $this->bannerImageHandler = $bannerImageHandler;
        $this->bannerStatisticClickHandler = $bannerStatisticClickHandler;
        $this->bannerAttachModelHandler = $bannerAttachModelHandler;
    }

    /**
     * @return BannerPositionHandler
     */
    public function getBannerPositionHandler(): BannerPositionHandler
    {
        return $this->bannerPositionHandler;
    }

    /**
     * @return BannerHandler
     */
    public function getBannerHandler(): BannerHandler
    {
        return $this->bannerHandler;
    }

    /**
     * @return BannerImageHandler
     */
    public function getBannerImageHandler(): BannerImageHandler
    {
        return $this->bannerImageHandler;
    }

    /**
     * @return BannerAttachModelHandler
     */
    public function getBannerAttachModelHandler(): BannerAttachModelHandler
    {
        return $this->bannerAttachModelHandler;
    }

    /**
     * @return BannerStatisticClickHandler
     */
    public function getBannerStatisticClickHandler(): BannerStatisticClickHandler
    {
        return $this->bannerStatisticClickHandler;
    }

    /**
     * @param BannerModel $bannerModel
     */
    public function deleteBanner(BannerModel $bannerModel): void
    {
        $this->getBannerAttachModelHandler()->deleteByBanner($bannerModel);

        $filter = (new BannerImageFilter())
            ->setBanner($bannerModel->id);

        foreach ($this->getBannerImageHandler()->listByCursor($filter) as $image) {
            $this->getBannerImageHandler()->delete($image);
        }

        $this->getBannerHandler()->delete($bannerModel);
    }

    /**
     * @param BannerModel $bannerModel
     */
    public function showBanner(BannerModel $bannerModel): void
    {
        $bannerModel->show_count = $bannerModel->show_count + 1;
        $this->bannerHandler->save($bannerModel);
    }

    /**
     * @param BannerModel $bannerModel
     * @param string|null $pageReferrer
     * @param array $properties
     */
    public function clickBanner(
        BannerModel $bannerModel,
        ?string $pageReferrer = null,
        array $properties = []
    ): void {
        $bannerModel->click_count = $bannerModel->click_count + 1;
        $this->bannerHandler->save($bannerModel);

        $statisticModel = $this->bannerStatisticClickHandler->newModel(
            $bannerModel,
            $pageReferrer,
            $properties
        );
        $this->bannerStatisticClickHandler->save($statisticModel);
    }
}
