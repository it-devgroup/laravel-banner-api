<?php

namespace ItDevgroup\LaravelBannerApi\Handler;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use ItDevgroup\LaravelBannerApi\Model\BannerAttachModel;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;

/**
 * Class BannerAttachModelHandler
 * @package ItDevgroup\LaravelBannerApi\Handler
 */
class BannerAttachModelHandler
{
    /**
     * @param BannerModel $model
     * @return BannerAttachModel[]|Collection
     */
    public function listByBanner(BannerModel $model): Collection
    {
        $builder = $this->getBuilder();

        $builder->where('banner_id', '=', $model->id);

        return $builder->get();
    }

    /**
     * @param Model $model
     * @return BannerAttachModel[]|Collection
     */
    public function listByModel(Model $model): Collection
    {
        $modelObj = new BannerAttachModel();
        $modelObj->model()->associate($model);

        $builder = $this->getBuilder();

        $builder->where('model_type', '=', $modelObj->model_type);
        $builder->where('model_id', '=', $modelObj->model_id);

        return $builder->get();
    }

    /**
     * @param int $id
     * @return Model|BannerAttachModel
     */
    public function byId(int $id): BannerAttachModel
    {
        $builder = $this->getBuilder();

        $builder->where('id', '=', $id);

        return $builder->firstOrFail();
    }

    /**
     * @param BannerModel $bannerModel
     * @param Model $model
     * @return BannerAttachModel|object
     */
    public function newOrGetModel(BannerModel $bannerModel, Model $model): BannerAttachModel
    {
        $modelObj = new BannerAttachModel();
        $modelObj->banner()->associate($bannerModel);
        $modelObj->model()->associate($model);
        $modelObj->rank = 0;

        $res = $this->findModel($modelObj);

        if ($res) {
            return $res;
        }

        return $modelObj;
    }

    /**
     * @param BannerModel $bannerModel
     * @param Model $model
     * @return BannerAttachModel|object
     */
    public function byBannerAndModel(BannerModel $bannerModel, Model $model): BannerAttachModel
    {
        $modelObj = new BannerAttachModel();
        $modelObj->banner()->associate($bannerModel);
        $modelObj->model()->associate($model);

        return $this->findModel($modelObj, true);
    }

    /**
     * @param BannerAttachModel $model
     * @return bool
     */
    public function save(BannerAttachModel $model): bool
    {
        if (!$model->rank) {
            $model->rank = $this->nextRank($model);
        }

        return $model->save();
    }

    /**
     * @param BannerAttachModel $model
     * @return bool
     */
    public function delete(BannerAttachModel $model): bool
    {
        return $model->delete();
    }

    /**
     * @param BannerModel $bannerModel
     * @param BannerAttachModel $model
     * @return bool
     */
    public function deleteByBannerAndModel(BannerModel $bannerModel, Model $model): bool
    {
        $modelObj = new BannerAttachModel();
        $modelObj->banner()->associate($bannerModel);
        $modelObj->model()->associate($model);

        $res = $this->findModel($modelObj);

        if (!$res) {
            return true;
        }

        return $res->delete();
    }

    /**
     * @param BannerModel $bannerModel
     * @return bool
     */
    public function deleteByBanner(BannerModel $bannerModel): bool
    {
        $builder = $this->getBuilder();

        $builder->where('banner_id', '=', $bannerModel->id);

        return $builder->delete();
    }

    /**
     * @param BannerAttachModel $model
     * @return bool
     */
    public function deleteByModel(Model $model): bool
    {
        $modelObj = new BannerAttachModel();
        $modelObj->model()->associate($model);

        $builder = $this->getBuilder();

        $builder->where('model_type', '=', $modelObj->model_type);
        $builder->where('model_id', '=', $modelObj->model_id);

        return $builder->delete();
    }

    /**
     * @return bool
     */
    public function deleteWithoutBanner(): bool
    {
        $builder = $this->getBuilder();

        $builder->doesntHave('banner');

        return $builder->delete();
    }

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return BannerAttachModel::query();
    }

    /**
     * @param BannerAttachModel $model
     * @param bool $exception
     * @return Model|null
     */
    protected function findModel(BannerAttachModel $model, bool $exception = false): ?Model
    {
        $builder = $this->getBuilder();

        $builder->where('banner_id', '=', $model->banner_id);
        $builder->where('model_type', '=', $model->model_type);
        $builder->where('model_id', '=', $model->model_id);

        if ($exception) {
            return $builder->firstOrFail();
        }

        return $builder->first();
    }

    /**
     * @param BannerAttachModel $model
     * @return int
     */
    protected function nextRank(BannerAttachModel $model): int
    {
        $builder = $this->getBuilder();

        $builder->where('model_type', '=', $model->model_type);
        $builder->where('model_id', '=', $model->model_id);

        return (int)$builder->max('rank') + 1;
    }
}
