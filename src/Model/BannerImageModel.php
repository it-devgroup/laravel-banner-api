<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use ItDevgroup\LaravelEntityFileTable\Helpers\FileHelper;
use ItDevgroup\LaravelEntityFileTable\Model\FileModel;

/**
 * Class BannerImageModel
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read int $id
 * @property int $banner_id
 * @property BannerModel $banner
 * @property string $type_name
 * @property string $filename
 * @property int $size
 * @property string $extension
 * @property string $mime
 * @property string $path
 * @property string $fullPath
 * @property string $file_driver
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BannerImageModel extends FileModel
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
        'size' => 'integer',
    ];

    /**
     * BannerImageModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('banner_api.table.image');

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
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        return FileHelper::url(
            $this->path,
            $this->file_driver
        );
    }

    /**
     * @param BannerModel $banner
     * @param string $path
     * @param string $fileDriver
     * @return self
     */
    public static function register(
        BannerModel $banner,
        string $path,
        string $fileDriver
    ): self {
        $model = new static();
        $model->path = $path;
        $model->file_driver = $fileDriver;
        $model->banner()->associate($banner);

        return $model;
    }
}
