<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Paginator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator as ZendPaginator;

class Paginator extends ZendPaginator
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
