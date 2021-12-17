<?php

namespace ItDevgroup\LaravelBannerApi\Console\Command;

use Carbon\Carbon;
use Illuminate\Console\Command;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\BannerServiceInterface;
use ItDevgroup\LaravelBannerApi\Model\BannerFilter;

/**
 * Class BannerPublishCommand
 * @package ItDevgroup\LaravelBannerApi\Console\Command
 */
class BannerToggleCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'banner:api:toggle';
    /**
     * @var string
     */
    protected $description = 'Banner activate/deactivate by date';
    /**
     * @var BannerServiceInterface
     */
    private BannerServiceInterface $bannerService;

    /**
     * BannerToggleCommand constructor.
     * @param BannerServiceInterface $bannerService
     */
    public function __construct(
        BannerServiceInterface $bannerService
    ) {
        parent::__construct();

        $this->bannerService = $bannerService;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $this->bannerActivate();
        $this->bannerDeactivate();
    }

    /**
     * @return void
     */
    private function bannerActivate(): void
    {
        $filter = (new BannerFilter())
            ->setStartAt(Carbon::now())
            ->setEndAt(Carbon::now())
            ->setIsActive(false);

        foreach ($this->bannerService->getBannerHandler()->listByCursor($filter) as $banner) {
            $banner->is_active = true;
            $this->bannerService->getBannerHandler()->save($banner);
        }
    }

    /**
     * @return void
     */
    private function bannerDeactivate(): void
    {
        $filter = (new BannerFilter())
            ->setStartAt(Carbon::now())
            ->setEndAt(Carbon::now())
            ->setIsActive(true)
            ->setDiffTimeModeAlternative(true);

        $option = (new BannerRequestOption())
            ->setSortField('id')
            ->setSortDirection('asc');

        foreach (
            $this->bannerService->getBannerHandler()->listByCursor($filter, $option) as $banner
        ) {
            $banner->is_active = false;
            $this->bannerService->getBannerHandler()->save($banner);
        }
    }
}
