<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractDeletableEntity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractDeletableEntity
{
    /**
     * @var Carbon
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var Carbon
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var Carbon
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Get the AbstractDeletableEntity createdAt
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the AbstractDeletableEntity createdAt
     * @param Carbon $createdAt
     * @return AbstractDeletableEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the AbstractDeletableEntity updatedAt
     * @return Carbon
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the AbstractDeletableEntity updatedAt
     * @param Carbon $updatedAt
     * @return AbstractDeletableEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the AbstractEntityWithDelete deletedAt
     * @return Carbon
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set the AbstractEntityWithDelete deletedAt
     * @param Carbon $deletedAt
     * @return AbstractDeletableEntity
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return AbstractDeletableEntity
     */
    public function createdAt()
    {
        $this->createdAt = Carbon::now();
        $this->updatedAt = $this->createdAt;
        return $this;
    }

    /**
     * @ORM\PreUpdate
     * @return AbstractDeletableEntity
     */
    public function updateAt()
    {
        $this->updatedAt = Carbon::now();
        return $this;
    }

    /**
     * @return AbstractDeletableEntity
     */
    public function deletedAt()
    {
        $this->deletedAt = Carbon::now();
        return $this;
    }

    /**
     * Is the AbstractEntityWithDelete deletedAt
     * @return bool
     */
    public function isDeletedAt()
    {
        return $this->deletedAt !== null;
    }
}
