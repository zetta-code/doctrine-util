<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil\Hydrator;

use Doctrine\Laminas\Hydrator\DoctrineObject;

class NoHandleTypeConversion extends DoctrineObject
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
