<?php

namespace ItDevgroup\LaravelBannerApi\Model;

/**
 * Class BannerPositionFilter
 * @package ItDevgroup\LaravelBannerApi\Model
 */
class BannerPositionFilter
{
    /**
     * @var string|null
     */
    protected ?string $title = null;
    /**
     * @var string|null
     */
    protected ?string $groupName = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return BannerPositionFilter
     */
    public function setTitle(?string $title): BannerPositionFilter
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    /**
     * @param string|null $groupName
     * @return BannerPositionFilter
     */
    public function setGroupName(?string $groupName): BannerPositionFilter
    {
        $this->groupName = $groupName;
        return $this;
    }
}
