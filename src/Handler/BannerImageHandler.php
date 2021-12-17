<?php

namespace ItDevgroup\LaravelBannerApi\Handler;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use ItDevgroup\LaravelBannerApi\BannerRequestOption;
use ItDevgroup\LaravelBannerApi\Event\BannerApiImageListBuilderEvent;
use ItDevgroup\LaravelBannerApi\Model\BannerImageFilter;
use ItDevgroup\LaravelBannerApi\Model\BannerImageModel;
use ItDevgroup\LaravelBannerApi\Model\BannerModel;
use ItDevgroup\LaravelEntityFileTable\EntityFileTableServiceInterface;
use ItDevgroup\LaravelEntityFileTable\Model\FileModel;

/**
 * Class BannerImageHandler
 * @package ItDevgroup\LaravelBannerApi\Handler
 */
class BannerImageHandler
{
    /**
     * @var string|null
     */
    private ?string $model;
    /**
     * @var string
     */
    private string $folder;
    /**
     * @var EntityFileTableServiceInterface
     */
    private EntityFileTableServiceInterface $entityFileTableService;

    /**
     * BannerImageHandler constructor.
     * @param EntityFileTableServiceInterface $entityFileTableService
     */
    public function __construct(
        EntityFileTableServiceInterface $entityFileTableService
    ) {
        $this->model = Config::get('banner_api.model.image');
        $this->folder = Config::get('banner_api.file.folder');
        $this->entityFileTableService = $entityFileTableService;
    }

    /**
     * @param string $folder
     */
    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
    }

    /**
     * @param BannerImageFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LengthAwarePaginator|Builder|Collection|BannerImageModel[]
     */
    public function list(
        ?BannerImageFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $builder = $this->listBuilder($filter, $option);

        if ($option && $option->getPage() && $option->getPerPage()) {
            return $builder->paginate(
                $option->getPerPage(),
                ['*'],
                'page',
                $option->getPage()
            );
        }

        return $builder->get();
    }

    /**
     * @param BannerImageFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return LazyCollection|BannerImageModel[]
     */
    public function listByCursor(
        ?BannerImageFilter $filter = null,
        ?BannerRequestOption $option = null
    ) {
        $builder = $this->listBuilder($filter, $option);

        return $builder->cursor();
    }

    /**
     * @param int $id
     * @return Model|BannerImageModel
     */
    public function byId(int $id): BannerImageModel
    {
        $builder = $this->getBuilder()
            ->where('id', '=', $id);

        return $builder->firstOrFail();
    }

    /**
     * @param BannerImageModel $model
     * @return bool
     */
    public function save(BannerImageModel $model): bool
    {
        if ($model->resource) {
            $this->entityFileTableService->save($model, false);
            $model->resource = null;
        }

        return $model->save();
    }

    /**
     * @param BannerImageModel $model
     * @return bool
     */
    public function delete(BannerImageModel $model): bool
    {
        $this->entityFileTableService->delete($model, false);

        return $model->delete();
    }

    /**
     * @param UploadedFile $file
     * @return FileModel|null
     */
    public function upload(UploadedFile $file): ?FileModel
    {
        $fileData = $this->entityFileTableService->upload($file, '');

        return $this->entityFileTableService->getFile($fileData);
    }

    /**
     * @param BannerModel $banner
     * @param FileModel $file
     * @return BannerImageModel
     */
    public function newModelWithFile(BannerModel $banner, FileModel $file): BannerImageModel
    {
        $path = $this->getPath($banner, $file);

        /** @var BannerImageModel $image */
        $image = $this->model::register(
            $banner,
            $path,
            $file->file_driver
        );
        $image->filename = $file->filename;
        $image->size = $file->size;
        $image->extension = $file->extension;
        $image->mime = $file->mime;
        $image->resource = $file->resource;

        return $image;
    }

    /**
     * @param BannerModel $banner
     * @param string $url
     * @return BannerImageModel
     */
    public function newModelWithUrl(BannerModel $banner, string $url): BannerImageModel
    {
        $file = $this->entityFileTableService->getFileExternal($url);

        /** @var BannerImageModel $image */
        $image = $this->model::register(
            $banner,
            $file->path,
            $file->file_driver
        );
        $image->filename = $file->filename;
        $image->size = $file->size;
        $image->extension = $file->extension;
        $image->mime = $file->mime;

        return $image;
    }

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->model::query();
    }

    /**
     * @param BannerImageFilter|null $filter
     * @param BannerRequestOption|null $option
     * @return Builder
     */
    protected function listBuilder(
        ?BannerImageFilter $filter = null,
        ?BannerRequestOption $option = null
    ): builder {
        $builder = $this->getBuilder();

        if ($filter && (!$option || !$option->isDisableFilter())) {
            $this->filter($builder, $filter);
        }

        if ($option && !$option->isDisableSort()) {
            $builder->orderBy(
                $option->getSortField(),
                $option->getSortDirection()
            );
        }

        Event::dispatch(
            new BannerApiImageListBuilderEvent(
                $builder,
                $filter,
                $option
            )
        );

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param BannerImageFilter|null $filter
     */
    protected function filter(Builder $builder, ?BannerImageFilter $filter = null): void
    {
        if (!$filter) {
            return;
        }

        if ($filter->getBanner()) {
            $builder->where(
                'banner_id',
                '=',
                $filter->getBanner()
            );
        }
        if ($filter->getType()) {
            $builder->where(
                $builder->raw('lower(type_name)'),
                '=',
                Str::lower($filter->getType())
            );
        }
        if ($filter->isWithoutBanner()) {
            $builder->where(
                function (Builder $builder) {
                    $builder->whereNull('banner_id');
                    $builder->orWhereDoesntHave('banner');
                }
            );
        }
    }

    /**
     * @param BannerModel $banner
     * @param FileModel $file
     * @return string
     */
    protected function getPath(BannerModel $banner, FileModel $file): string
    {
        return sprintf(
            '%s/%s/%s',
            $this->folder,
            $banner->id,
            $file->filename
        );
    }
}
