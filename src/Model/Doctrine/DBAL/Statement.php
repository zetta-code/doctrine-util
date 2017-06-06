<?php
/**
 * @link      http://github.com/zetta-repo/zetta-doctrineutil for the canonical source repository
 * @copyright Copyright (c) 2017 Zetta Code
 */

namespace Zetta\DoctrineUtil\Model\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Statement as DriverStatement;
use PDO;

class Statement implements \IteratorAggregate, DriverStatement
{
    private $_sql;
    private $_stmt;
    private $_conn;
    private $_values = [];
    private $_params = [];

    public function __construct($sql, Connection $conn)
    {
        $this->_sql = $sql;
        $this->_conn = $conn;
        $this->createStatement();
    }

    private function createStatement()
    {
        $this->_stmt = $this->_conn->prepareUnwrapped($this->_sql);
        foreach ($this->_values as $args) {
            $this->bindValue($args[0], $args[1], $args[2]);
        }
        foreach ($this->_params as $args) {
            $this->bindParam($args[0], $args[1], $args[2]);
        }
    }

    public function execute($params = null)
    {
        $stmt = null;
        $attempt = 0;
        $retry = true;
        while ($retry) {
            $retry = false;
            try {
                $stmt = $this->_stmt->execute($params);
            } catch (\Exception $e) {
                if ($this->_conn->validateReconnectAttempt($e, $attempt)) {
                    $this->_conn->close();
                    $this->createStatement();
                    $attempt++;
                    $retry = true;
                } else {
                    throw $e;
                }
            }
        }
        return $stmt;
    }

    function bindValue($param, $value, $type = null)
    {
        $this->_values[$param] = [$param, $value, $type];
        return $this->_stmt->bindValue($param, $value, $type);
    }

    function bindParam($column, &$variable, $type = PDO::PARAM_STR, $length = null)
    {
        $this->_values[$column] = [$column, &$variable, $type];
        return $this->_stmt->bindParam($column, $variable, $type);
    }

    function closeCursor()
    {
        return $this->_stmt->closeCursor();
    }

    function columnCount()
    {
        return $this->_stmt->columnCount();
    }

    function errorCode()
    {
        return $this->_stmt->errorCount();
    }

    function errorInfo()
    {
        return $this->_stmt->errorInfo();
    }

    function fetch($fetchStyle = PDO::FETCH_BOTH)
    {
        return $this->_stmt->fetch($fetchStyle);
    }

    function fetchAll($fetchStyle = PDO::FETCH_BOTH)
    {
        return $this->_stmt->fetchAll($fetchStyle);
    }

    function fetchColumn($columnIndex = 0)
    {
        return $this->_stmt->fetchColumn($columnIndex);
    }

    function rowCount()
    {
        return $this->_stmt->rowCount();
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        // This thin wrapper is necessary to shield against the weird signature
        // of PDOStatement::setFetchMode(): even if the second and third
        // parameters are optional, PHP will not let us remove it from this
        // declaration.
        if ($arg2 === null && $arg3 === null) {
            return parent::setFetchMode($fetchMode);
        }

        if ($arg3 === null) {
            return parent::setFetchMode($fetchMode, $arg2);
        }

        return parent::setFetchMode($fetchMode, $arg2, $arg3);
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->stmt;
    }
}
