<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;

/**
 * Class BannerModel
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read int $id
 * @property string $title
 * @property string $link
 * @property string $description
 * @property string $content
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property int $click_count
 * @property int $show_count
 * @property int $rank
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property BannerPositionModel[] $positions
 * @property BannerImageModel $image
 * @property BannerImageModel[] $images
 * @property BannerAttachModel[] $models
 */
class BannerModel extends Model
{
    /**
     * @var array
     */
    protected $dates = [
        'start_at',
        'end_at',
        'created_at',
        'updated_at',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'click_count' => 'integer',
        'show_count' => 'integer',
        'rank' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * BannerModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('banner_api.table.banner');

        parent::__construct($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(
            Config::get('banner_api.model.position'),
            Config::get('banner_api.table.banner_position'),
            'banner_id',
            'position_id',
        );
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        $builder = $this->hasOne(
            Config::get('banner_api.model.image'),
            'banner_id',
            'id'
        );

        $type = Config::get('banner_api.file.main_type');
        if ($type) {
            $newBuilder = $builder->clone();
            $newBuilder->where(
                function (Builder $builder) use ($type) {
                    $builder->where('type_name', '=', $type);
                }
            );

            if ($newBuilder->count()) {
                return $newBuilder;
            }

            unset($newBuilder);
        }

        return $builder;
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(
            Config::get('banner_api.model.image'),
            'banner_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function models(): HasMany
    {
        return $this->hasMany(
            BannerAttachModel::class,
            'banner_id',
            'id'
        );
    }

    /**
     * @param string $title
     * @return self
     */
    public static function register(
        string $title
    ): self {
        $model = new static();
        $model->title = $title;
        $model->click_count = 0;
        $model->show_count = 0;
        $model->rank = 0;
        $model->is_active = true;

        return $model;
    }
}
