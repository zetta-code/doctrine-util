<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\Service;

use Doctrine\ORM\EntityManager;

class LifecycleCallbacksManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $events;

    /**
     * DisableLifecycleCallbacks constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->events = [];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return LifecycleCallbacksManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param $entity
     */
    public function disableEvents($entity)
    {
        $entityName = get_class($entity);
        $this->events[$entityName] = $this->entityManager->getClassMetadata($entityName)->lifecycleCallbacks;

        // removes lifecycle events
        $this->entityManager->getClassMetadata($entityName)->setLifecycleCallbacks([]);
    }

    /**
     * @param $entity
     */
    public function enableEvents($entity)
    {
        $entityName = get_class($entity);

        if (isset($this->events[$entityName]) && is_array($this->events[$entityName]) && count($this->events[$entityName]) > 0) {
            $this->entityManager->getClassMetadata($entityName)->setLifecycleCallbacks($this->events[$entityName]);
            $this->events[$entityName] = [];
        }
    }
}
