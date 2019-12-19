<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Common;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Parameter;

final class DebugSql
{
    /**
     * Private constructor (prevents instantiation).
     */
    private function __construct()
    {
    }

    /**
     * Get query params list
     *
     * @param ArrayCollection $paramObj
     * @return array
     */
    private static function getParamsArray(ArrayCollection $paramObj): array
    {
        $parameters = array();
        /* @var $val Parameter */
        foreach ($paramObj as $val) {
            $parameters[$val->getName()] = $val->getValue();
        }

        return $parameters;
    }

    /**
     * Get SQL from query
     *
     * @param Query $query
     * @return string
     */
    public static function getFullSQL(Query $query): string
    {
        $sql = $query->getSql();
        $paramsList = self::getListParamsByDql($query->getDql());
        $paramsArr = self::getParamsArray($query->getParameters());
        $fullSql = '';
        for ($i = 0; $i < strlen($sql); $i++) {
            if ($sql[$i] == '?') {
                $nameParam = array_shift($paramsList);

                if (!isset($paramsArr[$nameParam])) {
                    $fullSql .= ':' . $nameParam;
                } elseif (is_string($paramsArr[$nameParam])) {
                    $fullSql .= '"' . addslashes($paramsArr[$nameParam]) . '"';
                } elseif (is_array($paramsArr[$nameParam])) {
                    $sqlArr = '';
                    foreach ($paramsArr[$nameParam] as $var) {
                        if (!empty($sqlArr))
                            $sqlArr .= ',';

                        if (is_string($var)) {
                            $sqlArr .= '"' . addslashes($var) . '"';
                        } else
                            $sqlArr .= $var;
                    }
                    $fullSql .= $sqlArr;
                } elseif (is_object($paramsArr[$nameParam])) {
                    if ($paramsArr[$nameParam] instanceof DateTimeInterface) {
                        $fullSql .= '\'' . $paramsArr[$nameParam]->format('Y-m-d H:i:s') . '\'';
                    } elseif (method_exists($paramsArr[$nameParam], 'getId')) {
                        $fullSql .= $paramsArr[$nameParam]->getId();
                    } else {
                        $fullSql .= $paramsArr[$nameParam]->__toString();
                    }

                } else
                    $fullSql .= $paramsArr[$nameParam];

            } else {
                $fullSql .= $sql[$i];
            }
        }
        return $fullSql;
    }

    /**
     * @param string $dql
     * @return array
     */
    public static function getListParamsByDql(string $dql): array
    {
        $parsedDql = preg_split('/:/', $dql);
        $length = count($parsedDql);
        $parameters = array();
        for ($i = 1; $i < $length; $i++) {
            if (preg_match('/^[a-zA-Z]+$/', $parsedDql[$i][0])) {
                $param = (preg_split('/[\' )]/', $parsedDql[$i]));
                $parameters[] = $param[0];
            }
        }

        return $parameters;
    }
}
