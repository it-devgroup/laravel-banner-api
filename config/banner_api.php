<?php

return [
    'table' => [
        /*
        |--------------------------------------------------------------------------
        | Table name for banners
        |--------------------------------------------------------------------------
        */
        'banner' => 'banners',

        /*
        |--------------------------------------------------------------------------
        | Table name for positions
        |--------------------------------------------------------------------------
        */
        'position' => 'banner_positions',

        /*
        |--------------------------------------------------------------------------
        | Table name for linked of banners and positions
        |--------------------------------------------------------------------------
        */
        'banner_position' => 'banners_positions',

        /*
        |--------------------------------------------------------------------------
        | Table name for banner images
        |--------------------------------------------------------------------------
        */
        'image' => 'banner_images',

        /*
        |--------------------------------------------------------------------------
        | Table name for banner statistic clicks
        |--------------------------------------------------------------------------
        */
        'statistic_click' => 'banner_statistic_clicks',

        /*
        |--------------------------------------------------------------------------
        | Table name for banner attach models
        |--------------------------------------------------------------------------
        */
        'attach_model' => 'banner_attach_models',
    ],

    'model' => [
        /*
        |--------------------------------------------------------------------------
        | Model for table banners
        |--------------------------------------------------------------------------
        | Default: \ItDevgroup\LaravelBannerApi\Model\BannerModel::class
        | Change to your custom class if you need to extend the model
        | A custom class must inherit the base class \ItDevgroup\LaravelBannerApi\Model\BannerModel
        */
        'banner' => \ItDevgroup\LaravelBannerApi\Model\BannerModel::class,

        /*
        |--------------------------------------------------------------------------
        | Model for table positions
        |--------------------------------------------------------------------------
        | Default: \ItDevgroup\LaravelBannerApi\Model\BannerPositionModel::class
        | Change to your custom class if you need to extend the model
        | A custom class must inherit the base class \ItDevgroup\LaravelBannerApi\Model\BannerPositionModel
        */
        'position' => \ItDevgroup\LaravelBannerApi\Model\BannerPositionModel::class,

        /*
        |--------------------------------------------------------------------------
        | Model for table images
        |--------------------------------------------------------------------------
        | Default: \ItDevgroup\LaravelBannerApi\Model\BannerImageModel::class
        | Change to your custom class if you need to extend the model
        | A custom class must inherit the base class \ItDevgroup\LaravelBannerApi\Model\BannerImageModel
        */
        'image' => \ItDevgroup\LaravelBannerApi\Model\BannerImageModel::class,

        /*
        |--------------------------------------------------------------------------
        | Model for table statistic clicks
        |--------------------------------------------------------------------------
        | Default: \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel::class
        | Change to your custom class if you need to extend the model
        | A custom class must inherit the base class \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel
        */
        'statistic_click' => \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel::class,

        /*
        |--------------------------------------------------------------------------
        | Model for table attach models (disabled setting)
        |--------------------------------------------------------------------------
        | Default: \ItDevgroup\LaravelBannerApi\Model\BannerAttachModel::class
        | Change to your custom class if you need to extend the model
        | A custom class must inherit the base class \ItDevgroup\LaravelBannerApi\Model\BannerAttachModel
        */
        'attach_model' => \ItDevgroup\LaravelBannerApi\Model\BannerAttachModel::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Config for statistic click
    |--------------------------------------------------------------------------
    */
    'statistic_click' => [
        /*
        |--------------------------------------------------------------------------
        | Banner fields for save to property field
        |--------------------------------------------------------------------------
        */
        'banner_fields' => [
            'title',
            'link',
        ],
        /*
        |--------------------------------------------------------------------------
        | Grouping mode of save data
        |--------------------------------------------------------------------------
        | Class: \ItDevgroup\LaravelBannerApi\BannerServiceInterface
        | modes (constant):
        |   STATISTIC_CLICK_SAVE_MODE_DEFAULT - no grouping
        |   STATISTIC_CLICK_SAVE_MODE_MINUTE - grouping by minutes
        |   STATISTIC_CLICK_SAVE_MODE_HOUR - grouping by hours
        |   STATISTIC_CLICK_SAVE_MODE_HOUR - grouping by hours
        |   STATISTIC_CLICK_SAVE_MODE_DAY - grouping by days
        | Example:
        |   \ItDevgroup\LaravelBannerApi\BannerServiceInterface::STATISTIC_CLICK_SAVE_MODE_DEFAULT
        */
        'save_mode' => \ItDevgroup\LaravelBannerApi\BannerServiceInterface::STATISTIC_CLICK_SAVE_MODE_DEFAULT
    ],

    /*
    |--------------------------------------------------------------------------
    | Config for images
    |--------------------------------------------------------------------------
    */
    'file' => [
        /*
        |--------------------------------------------------------------------------
        | Root folder for upload all images
        |--------------------------------------------------------------------------
        */
        'folder' => env('BANNER_API_IMAGE_FOLDER', 'banners'),
        /*
        |--------------------------------------------------------------------------
        | Main image type (optional)
        |--------------------------------------------------------------------------
        | If empty - search first image of current banner without field of type
        | If not empty - search first image of current banner with set value in field of type
        */
        'main_type' => env('BANNER_API_IMAGE_MAIN_TYPE')
    ],

    /*
    |--------------------------------------------------------------------------
    | Query setup
    |--------------------------------------------------------------------------
    */
    'query' => [
        'operator' => [
            /*
            |--------------------------------------------------------------------------
            | Operator LIKE for sql
            |--------------------------------------------------------------------------
            | Default: like
            | If use PostgreSQL need set ilike
            */
            'like' => 'like'
        ]
    ],

    'optimization' => [
        'image' => [
            /*
            |--------------------------------------------------------------------------
            | Enabled clear: images
            |--------------------------------------------------------------------------
            */
            'clear' => env('BANNER_API_OPTIMIZATION_IMAGE_CLEAR', true)
        ],
        'attach_model' => [
            /*
            |--------------------------------------------------------------------------
            | Enabled clear: attach model
            |--------------------------------------------------------------------------
            */
            'clear' => env('BANNER_API_OPTIMIZATION_ATTACH_MODEL_CLEAR', true)
        ],
        'statistic_click' => [
            /*
            |--------------------------------------------------------------------------
            | Enabled clear: statistic click
            |--------------------------------------------------------------------------
            */
            'clear' => env('BANNER_API_OPTIMIZATION_STATISTIC_CLICK_CLEAR', true),
            /*
            |--------------------------------------------------------------------------
            | Clear older entries
            |--------------------------------------------------------------------------
            | Entries older than the specified number of months will be automatically deleted via the command
            | If null - not clear old entries
            */
            'clear_rows_old_month' => env('BANNER_API_OPTIMIZATION_STATISTIC_CLICK_MONTH', 12)
        ]
    ]
];
