<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

/**
 * Class BannerStatisticClickModel
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read int $id
 * @property int $banner_id
 * @property BannerModel $banner
 * @property string $page_referrer
 * @property int $click_count
 * @property array $properties
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BannerStatisticClickModel extends Model
{
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'click_count' => 'integer',
        'properties' => 'array',
    ];

    /**
     * BannerStatisticClickModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('banner_api.table.statistic_click');

        parent::__construct($attributes);
    }

    /**
     * @return BelongsTo
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(
            Config::get('banner_api.model.banner'),
            'banner_id',
            'id',
        );
    }

    /**
     * @param BannerModel $banner
     * @return self
     */
    public static function register(
        BannerModel $banner
    ): self {
        $model = new static();
        $model->click_count = 1;
        $model->banner()->associate($banner);

        return $model;
    }
}
