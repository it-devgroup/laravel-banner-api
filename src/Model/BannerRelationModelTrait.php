<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait BannerRelationModelTrait
 * @package ItDevgroup\LaravelBannerApi\Model
 * @property-read BannerAttachModel[] banners
 */
trait BannerRelationModelTrait
{
    /**
     * @return MorphMany
     */
    public function banners(): MorphMany
    {
        return $this->morphMany(
            BannerAttachModel::class,
            'model',
            'model_type',
            'model_id'
        )->orderBy('rank');
    }
}
