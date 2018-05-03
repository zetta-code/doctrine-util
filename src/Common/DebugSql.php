<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Common;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @param  ArrayCollection $paramObj
     * @return array
     */
    private static function getParamsArray($paramObj)
    {
        $parameters = array();
        foreach ($paramObj as $val) {
            /* @var $val \Doctrine\ORM\Query\Parameter */
            $parameters[$val->getName()] = $val->getValue();
        }

        return $parameters;
    }

    /**
     * Get SQL from query
     *
     * @param \Doctrine\ORM\Query $query
     * @return string
     */
    public static function getFullSQL($query)
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
                    switch (get_class($paramsArr[$nameParam])) {
                        case 'DateTime':
                            $fullSql .= '\'' . $paramsArr[$nameParam]->format('Y-m-d H:i:s') . '\'';
                            break;
                        default:
                            $fullSql .= $paramsArr[$nameParam]->getId();
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
     * @param $dql
     * @return array
     */
    public static function getListParamsByDql($dql)
    {
        $parsedDql = preg_split('/:/', $dql);
        $length = count($parsedDql);
        $parameters = array();
        for ($i = 1; $i < $length; $i++) {
            if (ctype_alpha($parsedDql[$i][0])) {
                $param = (preg_split('/[\' \' )]/', $parsedDql[$i]));
                $parameters[] = $param[0];
            }
        }

        return $parameters;
    }
}
