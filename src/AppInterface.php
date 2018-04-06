<?php

namespace DrMVC;

use Psr\Container\ContainerInterface;
use DrMVC\Config\ConfigInterface;

interface AppInterface extends ContainerInterface
{
    /**
     * @param   string $container
     * @param   string $object
     * @param   ConfigInterface $config
     * @return  AppInterface
     */
    public function set(string $container, string $object, ConfigInterface $config): AppInterface;
}
