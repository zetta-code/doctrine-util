<?php
/**
 * @link      http://github.com/zetta-repo/zetta-doctrineutil for the canonical source repository
 * @copyright Copyright (c) 2017 Zetta Code
 */

namespace Zetta\DoctrineUtil\Hydrator;

class DoctrineObject extends \DoctrineModule\Stdlib\Hydrator\DoctrineObject
{
    /**
     * No handle various type conversions
     *
     * @param  mixed $value
     * @param  string $typeOfField
     * @return mixed
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        return $value;
    }
}