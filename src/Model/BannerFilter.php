<?php

namespace ItDevgroup\LaravelBannerApi\Model;

use Carbon\Carbon;

/**
 * Class BannerFilter
 * @package ItDevgroup\LaravelBannerApi\Model
 */
class BannerFilter
{
    /**
     * @var int[]|null
     */
    protected ?array $ids = null;
    /**
     * @var string|null
     */
    protected ?string $title = null;
    /**
     * @var int[]|null
     */
    protected ?array $positions = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $startAt = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $endAt = null;
    /**
     * @var bool|null
     */
    protected ?bool $isActive = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $createdAtFrom = null;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $createdAtTo = null;
    /**
     * @var bool
     */
    protected bool $diffTimeModeAlternative = false;
    /**
     * @var string|null
     */
    protected ?string $modelType = null;
    /**
     * @var int|null
     */
    protected ?int $modelId = null;

    /**
     * @return int[]|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param int[]|null $ids
     * @return BannerFilter
     */
    public function setIds(?array $ids): BannerFilter
    {
        $this->ids = $ids;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return BannerFilter
     */
    public function setTitle(?string $title): BannerFilter
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getPositions(): ?array
    {
        return $this->positions;
    }

    /**
     * @param int[]|null $positions
     * @return BannerFilter
     */
    public function setPositions(?array $positions): BannerFilter
    {
        $this->positions = $positions;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getStartAt(): ?Carbon
    {
        return $this->startAt;
    }

    /**
     * @param Carbon|null $startAt
     * @return BannerFilter
     */
    public function setStartAt(?Carbon $startAt): BannerFilter
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getEndAt(): ?Carbon
    {
        return $this->endAt;
    }

    /**
     * @param Carbon|null $endAt
     * @return BannerFilter
     */
    public function setEndAt(?Carbon $endAt): BannerFilter
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     * @return BannerFilter
     */
    public function setIsActive(?bool $isActive): BannerFilter
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAtFrom(): ?Carbon
    {
        return $this->createdAtFrom;
    }

    /**
     * @param Carbon|null $createdAtFrom
     * @return BannerFilter
     */
    public function setCreatedAtFrom(?Carbon $createdAtFrom): BannerFilter
    {
        $this->createdAtFrom = $createdAtFrom;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAtTo(): ?Carbon
    {
        return $this->createdAtTo;
    }

    /**
     * @param Carbon|null $createdAtTo
     * @return BannerFilter
     */
    public function setCreatedAtTo(?Carbon $createdAtTo): BannerFilter
    {
        $this->createdAtTo = $createdAtTo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDiffTimeModeAlternative(): bool
    {
        return $this->diffTimeModeAlternative;
    }

    /**
     * @param bool $diffTimeModeAlternative
     * @return BannerFilter
     */
    public function setDiffTimeModeAlternative(bool $diffTimeModeAlternative): BannerFilter
    {
        $this->diffTimeModeAlternative = $diffTimeModeAlternative;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModelType(): ?string
    {
        return $this->modelType;
    }

    /**
     * @param string|null $modelType
     * @return BannerFilter
     */
    public function setModelType(?string $modelType): BannerFilter
    {
        $this->modelType = $modelType;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getModelId(): ?int
    {
        return $this->modelId;
    }

    /**
     * @param int|null $modelId
     * @return BannerFilter
     */
    public function setModelId(?int $modelId): BannerFilter
    {
        $this->modelId = $modelId;
        return $this;
    }
}
