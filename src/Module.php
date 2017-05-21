<?php
/**
 * @link      http://github.com/zetta-repo/zetta-doctrineutil for the canonical source repository
 * @copyright Copyright (c) 2016 Zetta Code
 */

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
