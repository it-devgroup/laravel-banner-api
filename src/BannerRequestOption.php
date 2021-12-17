<?php

namespace ItDevgroup\LaravelBannerApi;

/**
 * Class BannerRequestOption
 * @package ItDevgroup\LaravelBannerApi
 */
class BannerRequestOption
{
    /**
     * @var string
     */
    private string $sortField = 'id';
    /**
     * @var string
     */
    private string $sortDirection = 'ASC';
    /**
     * @var int|null
     */
    private ?int $page = null;
    /**
     * @var int|null
     */
    private ?int $perPage = null;
    /**
     * @var bool
     */
    private bool $isDisableFilter = false;
    /**
     * @var bool
     */
    private bool $isDisableSort = false;

    /**
     * @return string
     */
    public function getSortField(): string
    {
        return $this->sortField;
    }

    /**
     * @param string $sortField
     * @return BannerRequestOption
     */
    public function setSortField(string $sortField): BannerRequestOption
    {
        $this->sortField = $sortField;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @param string $sortDirection
     * @return BannerRequestOption
     */
    public function setSortDirection(string $sortDirection): BannerRequestOption
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     * @return BannerRequestOption
     */
    public function setPage(?int $page): BannerRequestOption
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    /**
     * @param int|null $perPage
     * @return BannerRequestOption
     */
    public function setPerPage(?int $perPage): BannerRequestOption
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableFilter(): bool
    {
        return $this->isDisableFilter;
    }

    /**
     * @param bool $isDisableFilter
     * @return BannerRequestOption
     */
    public function setIsDisableFilter(bool $isDisableFilter): BannerRequestOption
    {
        $this->isDisableFilter = $isDisableFilter;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableSort(): bool
    {
        return $this->isDisableSort;
    }

    /**
     * @param bool $isDisableSort
     * @return BannerRequestOption
     */
    public function setIsDisableSort(bool $isDisableSort): BannerRequestOption
    {
        $this->isDisableSort = $isDisableSort;
        return $this;
    }
}
