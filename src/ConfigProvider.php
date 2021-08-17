<?php

/**
 * @link      https://github.com/zetta-code/doctrine-util for the canonical source repository
 * @copyright Copyright (c) 2018 Zetta Code
 */

declare(strict_types=1);

namespace Zetta\DoctrineUtil;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke()
    {
        return $this->getConfig();
    }

    public function getConfig()
    {
        return [
            'doctrine' => [
                'driver' => [
                    __NAMESPACE__ . '_driver' => [
                        'class' => AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [dirname(__DIR__) . '/src/Entity'],
                    ],
                    'orm_default' => [
                        'drivers' => [
                            __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                        ],
                    ],
                ],
            ],
        ];
    }
}
