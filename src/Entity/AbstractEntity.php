<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Entity;

use Doctrine\ORM\Mapping as ORM;
use Jenssegers\Date\Date;

/**
 * AbstractEntity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity
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
     * Get the AbstractEntity createdAt
     * @return Date
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the AbstractEntity createdAt
     * @param Date $createdAt
     * @return AbstractEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the AbstractEntity updatedAt
     * @return Date
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the AbstractEntity updatedAt
     * @param Date $updatedAt
     * @return AbstractEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return AbstractEntity
     */
    public function createdAt()
    {
        $this->createdAt = Date::now();
        $this->updatedAt = $this->createdAt;
        return $this;
    }

    /**
     * @ORM\PreUpdate
     * @return AbstractEntity
     */
    public function updateAt()
    {
        $this->updatedAt = Date::now();
        return $this;
    }
}
