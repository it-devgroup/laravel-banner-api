<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class BannerPositionModel
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read int $id
 * @property string $title
 * @property string $group_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BannerPositionModel extends Model
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
    ];

    /**
     * BannerPositionModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('banner_api.table.position');

        parent::__construct($attributes);
    }

    /**
     * @param string $title
     * @return BannerPositionModel
     */
    public static function register(
        string $title
    ): self {
        $model = new static();
        $model->title = $title;

        return $model;
    }
}
