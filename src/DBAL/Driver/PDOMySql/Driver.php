<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\DBAL\Driver\PDOMySql;

use Exception;

class Driver extends \Doctrine\DBAL\Driver\PDOMySql\Driver
{
    /**
     * @return string[]
     */
    public function getReconnectExceptions(): array
    {
        return [
            'SQLSTATE[HY000]: General error: 2006 MySQL server has gone away',
            'SQLSTATE[HY000]: General error: 2013 Lost connection to MySQL server during query',
            'SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known'
        ];
    }

    /**
     * @param Exception $x
     * @return bool
     */
    public function shouldStall(Exception $x): bool
    {
        return stristr($x->getMessage(), 'php_network_getaddresses') !== false;
    }
}
