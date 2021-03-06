<?php
/**
 * @link      http://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

namespace Zetta\DoctrineUtil\Hydrator;

class DoctrineObject extends \DoctrineModule\Stdlib\Hydrator\DoctrineObject
{
    /**
     * No handle various type conversions
     *
     * @param mixed $value
     * @param string $typeOfField
     * @return mixed
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        return $value;
    }
}
