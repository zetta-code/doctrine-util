<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\DBAL;

use Doctrine\DBAL\DBALException;
use Exception;

/**
 * Class Statement
 * @package Zetta\DoctrineUtil\DBAL
 */
class Statement extends \Doctrine\DBAL\Statement
{
    /**
     * Represents the SQL CHAR, VARCHAR, or other string data type.
     * @link https://php.net/manual/en/pdo.constants.php#pdo.constants.param-str
     */
    const PARAM_STR = 2;

    /**
     * @var array
     */
    protected $_values = [];

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @return void
     */
    protected function resetStatement()
    {
        $this->stmt = $this->conn->getWrappedConnection()->prepare($this->sql);
        foreach ($this->_values as $name => $value) {
            parent::bindValue($name, $value['value'], $value['type']);
        }
        foreach ($this->_params as $name => $value) {
            parent::bindParam($name, $value['value'], $value['type'], $value['length']);
        }
    }

    /**
     * @inheritdoc
     */
    public function execute($params = null)
    {
        $attempt = 0;
        $retry = true;
        $stmt = false;
        if (is_array($params)) {
            $this->params = $params;
        }

        $logger = $this->conn->getConfiguration()->getSQLLogger();
        if ($logger) {
            $logger->startQuery($this->sql, $this->params, $this->types);
        }

        while ($retry) {
            $retry = false;
            try {
                $stmt = $this->stmt->execute($params);
            } catch (Exception $ex) {
                if ($this->conn instanceof Connection && $this->conn->validateReconnectAttempt($ex, $attempt)) {
                    $this->conn->close();
                    $this->resetStatement();
                    $attempt++;
                    $retry = true;
                } else {
                    if ($logger) {
                        $logger->stopQuery();
                    }
                    throw DBALException::driverExceptionDuringQuery(
                        $this->conn->getDriver(),
                        $ex,
                        $this->sql,
                        $this->conn->resolveParams($this->params, $this->types)
                    );
                }
            }
        }

        if ($logger) {
            $logger->stopQuery();
        }
        $this->_params = [];
        $this->_values = [];
        $this->params = [];
        $this->types = [];

        return $stmt;
    }

    /**
     * @inheritdoc
     */
    public function bindValue($name, $value, $type = null)
    {
        $this->_values[$name] = ['value' => $value, 'type' => $type];
        return parent::bindValue($name, $value, $type);
    }

    /**
     * @inheritdoc
     */
    public function bindParam($name, &$var, $type = self::PARAM_STR, $length = null)
    {
        $this->_params[$name] = ['value' => &$var, 'type' => $type, 'length' => $length];
        return parent::bindParam($name, $value, $type, $length);
    }
}
