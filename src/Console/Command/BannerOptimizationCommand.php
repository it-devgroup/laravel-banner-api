<?php

namespace ItDevgroup\LaravelBannerApi\Console\Command;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use ItDevgroup\LaravelBannerApi\BannerServiceInterface;
use ItDevgroup\LaravelBannerApi\Model\BannerImageFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickFilter;

/**
 * Class BannerClearImageWithoutBannerCommand
 * @package ItDevgroup\LaravelBannerApi\Console\Command
 */
class BannerOptimizationCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'banner:api:optimization';
    /**
     * @var string
     */
    protected $description = 'Clear all banner relation models if they without banner';
    /**
     * @var BannerServiceInterface
     */
    private BannerServiceInterface $bannerService;

    /**
     * BannerClearImageWithoutBannerCommand constructor.
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
        $this->clearImage();
        $this->clearAttachModel();
        $this->clearStatisticClick();
    }

    /**
     * @return void
     */
    private function clearImage()
    {
        if (!Config::get('banner_api.optimization.image.clear')) {
            return;
        }

        $filter = (new BannerImageFilter())
            ->setWithoutBanner(true);

        foreach ($this->bannerService->getBannerImageHandler()->listByCursor($filter) as $image) {
            $this->bannerService->getBannerImageHandler()->delete($image);
        }
    }

    /**
     * @return void
     */
    private function clearAttachModel()
    {
        if (!Config::get('banner_api.optimization.attach_model.clear')) {
            return;
        }

        $this->bannerService->getBannerAttachModelHandler()->deleteWithoutBanner();
    }

    /**
     * @return void
     */
    private function clearStatisticClick()
    {
        if (!Config::get('banner_api.optimization.statistic_click.clear')) {
            return;
        }

        $month = (int)Config::get('banner_api.optimization.statistic_click.clear_rows_old_month');

        if (!$month) {
            return;
        }

        $filter = (new BannerStatisticClickFilter())
            ->setCreatedAtTo(Carbon::now()->subMonths($month));

        foreach ($this->bannerService->getBannerStatisticClickHandler()->listByCursor($filter) as $row) {
            $this->bannerService->getBannerStatisticClickHandler()->delete($row);
        }
    }
}
