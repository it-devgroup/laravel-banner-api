<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

/**
 * Class BannerAttachModel
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read int $id
 * @property int $banner_id
 * @property BannerModel $banner
 * @property string $model_type
 * @property int $model_id
 * @property int $rank
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Model $model
 */
class BannerAttachModel extends Model
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
        'rank' => 'integer'
    ];

    /**
     * BannerAttachModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('banner_api.table.attach_model');

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
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }

    /**
     * @param BannerModel $banner
     * @param Model $model
     * @return self
     */
    public static function register(
        BannerModel $banner,
        Model $model
    ): self {
        $m = new static();
        $m->banner()->associate($banner);
        $m->model()->associate($model);
        $m->rank = 0;

        return $m;
    }
}
