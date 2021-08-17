<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil;

class Module
{
    /**
     * Provide application configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();
        return $provider->getConfig();
    }
}
