<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\Paginator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Laminas\Paginator\Paginator as LaminasPaginator;

class Paginator extends LaminasPaginator
{
    /**
     * Paginator constructor.
     * @param QueryBuilder $queryBuilder
     */
    public function __construct($queryBuilder)
    {
        parent::__construct(new DoctrinePaginator(new DoctrineORMPaginator($queryBuilder)));
    }
}
