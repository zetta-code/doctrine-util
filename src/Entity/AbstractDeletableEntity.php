<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Entity;

use Doctrine\ORM\Mapping as ORM;
use Jenssegers\Date\Date;

/**
 * AbstractDeletableEntity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractDeletableEntity
{
    /**
     * @var Date
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var Date
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var Date
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Get the AbstractDeletableEntity createdAt
     * @return Date
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the AbstractDeletableEntity createdAt
     * @param Date $createdAt
     * @return AbstractDeletableEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the AbstractDeletableEntity updatedAt
     * @return Date
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the AbstractDeletableEntity updatedAt
     * @param Date $updatedAt
     * @return AbstractDeletableEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the AbstractEntityWithDelete deletedAt
     * @return Date
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set the AbstractEntityWithDelete deletedAt
     * @param Date $deletedAt
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
        $this->createdAt = Date::now();
        $this->updatedAt = $this->createdAt;
        return $this;
    }

    /**
     * @ORM\PreUpdate
     * @return AbstractDeletableEntity
     */
    public function updateAt()
    {
        $this->updatedAt = Date::now();
        return $this;
    }

    /**
     * @return AbstractDeletableEntity
     */
    public function deletedAt()
    {
        $this->deletedAt = Date::now();
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
