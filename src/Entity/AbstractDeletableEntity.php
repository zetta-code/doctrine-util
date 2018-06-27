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
abstract class AbstractDeletableEntity extends AbstractEntity
{
    /**
     * @var Date
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

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
     * @return AbstractDeletableEntity
     */
    public function deletedAt()
    {
        $this->deletedAt = Date::now();
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->deletedAt === null;
    }
}
