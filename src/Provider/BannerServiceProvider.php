<?php

namespace ItDevgroup\LaravelBannerApi\Provider;

use Illuminate\Support\ServiceProvider;
use ItDevgroup\LaravelBannerApi\BannerService;
use ItDevgroup\LaravelBannerApi\BannerServiceInterface;
use ItDevgroup\LaravelBannerApi\Console\Command\BannerOptimizationCommand;
use ItDevgroup\LaravelBannerApi\Console\Command\BannerPublishCommand;
use ItDevgroup\LaravelBannerApi\Console\Command\BannerToggleCommand;

/**
 * Class BannerApiServiceProvider
 * @package ItDevgroup\LaravelBannerApi\Provider
 */
class BannerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->loadCustomClasses();
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->loadCustomCommands();
        $this->loadCustomConfig();
        $this->loadCustomPublished();
    }

    /**
     * @return void
     */
    private function loadCustomCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    BannerPublishCommand::class,
                    BannerToggleCommand::class,
                    BannerOptimizationCommand::class,
                ]
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/banner_api.php',
            'banner_api'
        );
    }

    /**
     * @return void
     */
    private function loadCustomPublished()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../config' => base_path('config')
                ],
                'config'
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomClasses()
    {
        $this->app->singleton(BannerServiceInterface::class, BannerService::class);
    }
}
