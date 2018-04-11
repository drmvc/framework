<?php

namespace DrMVC\Framework;

use Psr\Container\ContainerInterface;
use DrMVC\Config\ConfigInterface;

interface ContainersInterface extends ContainerInterface
{
    /**
     * @param   string $container
     * @param   string|object $object
     * @param   ConfigInterface $config
     * @return  ContainersInterface
     */
    public function set(string $container, $object, ConfigInterface $config = null): ContainersInterface;

}
