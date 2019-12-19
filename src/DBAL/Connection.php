<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\DBAL;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Exception;

class Connection extends \Doctrine\DBAL\Connection
{
    /**
     * @var int
     */
    protected $reconnectAttempts = 0;

    /**
     * @inheritdoc
     */
    public function __construct(array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null)
    {
        if (isset($params['driverOptions']['x_reconnect_attempts']) && method_exists($driver, 'getReconnectExceptions')) {
            // sanity check: 0 if no exceptions are available
            $reconnectExceptions = $driver->getReconnectExceptions();
            $this->reconnectAttempts = empty($reconnectExceptions) ? 0 : (int)$params['driverOptions']['x_reconnect_attempts'];
        }
        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * @inheritdoc
     */
    public function executeQuery($query, array $params = [], $types = [], QueryCacheProfile $qcp = null)
    {
        return $this->executeWrapped('executeQuery', $query, $params, $types, $qcp);
    }

    /**
     * @inheritdoc
     */
    public function query()
    {
        return $this->executeWrapped('query');
    }

    /**
     * @inheritdoc
     */
    public function executeUpdate($query, array $params = [], array $types = [])
    {
        return $this->executeWrapped('executeUpdate', $query, $params, $types);
    }

    /**
     * @inheritdoc
     */
    public function beginTransaction()
    {
        $this->executeWrapped('beginTransaction');
    }

    /**
     * @param Exception $e
     * @param $attempt
     * @return bool
     */
    public function validateReconnectAttempt(Exception $e, $attempt)
    {
        if ($this->getTransactionNestingLevel() <= 1 && $this->reconnectAttempts && $attempt < $this->reconnectAttempts) {
            $reconnectExceptions = $this->_driver->getReconnectExceptions();
            $message = $e->getMessage();
            if (!empty($reconnectExceptions)) {
                foreach ($reconnectExceptions as $reconnectException) {
                    if (strpos($message, $reconnectException) !== false) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param $method
     * @param null $query
     * @param array $params
     * @param array $types
     * @param QueryCacheProfile|null $qcp
     * @return mixed
     * @throws Exception
     */
    private function executeWrapped($method, $query = null, array $params = [], $types = [], QueryCacheProfile $qcp = null)
    {
        $stmt = null;
        $attempt = 0;
        $retry = true;
        while ($retry) {
            $retry = false;
            try {
                if ($query !== null && $qcp !== null) {
                    $stmt = parent::$method($query, $params, $types, $qcp);
                } elseif ($query !== null && $qcp === null) {
                    $stmt = parent::$method($query, $params, $types);
                } else {
                    $stmt = parent::$method();
                }

            } catch (Exception $e) {
                error_log('DBAL EXCEPTION THROWN [' . $this->getTransactionNestingLevel() . ']:' . $e->getMessage());
                if ($this->validateReconnectAttempt($e, $attempt)) {
                    error_log('    OK - successfully validated');
                    $this->close();
                    $attempt++;

                    if ($this->_driver->shouldStall($e)) {
                        error_log('    waitstate deemed beneficial, sleeping 5 seconds...');
                        sleep(5);
                    }

                    $retry = true;
                } else {
                    error_log('    FAIL - could not be validated');
                    throw $e;
                }
            }
        }

        return $stmt;
    }
}
