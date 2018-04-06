<?php

namespace DrMVC;

//use Psr\Container\ContainerInterface;
//use Psr\Container\NotFoundExceptionInterface;

use DrMVC\Config\ConfigInterface;

class App implements AppInterface
{
    private $_containers = [];

    public function __construct(ConfigInterface $config)
    {
        $this->setContainer('config', $config);
    }

    /**
     * @param   string $name
     * @return  mixed
     */
    private function getContainer(string $name)
    {
        return $this->_containers[$name];
    }

    /**
     * @param   string $name
     * @param   object $object
     * @return  AppInterface
     */
    private function setContainer(string $name, $object): AppInterface
    {
        $this->_containers[$name] = $object;
        return $this;
    }

    /**
     * PSR-11 set container
     *
     * @param   string $container
     * @param   string $object
     * @param   ConfigInterface $config
     * @return  AppInterface
     */
    public function set(string $container, string $object, ConfigInterface $config): AppInterface
    {
        $objectConfig = $config->get($object);

        if (null !== $objectConfig) {
            $class = '\\DrMVC\\' . $object;
            $this->setContainer($container, new $class($config));
        }

        return $this;
    }

    /**
     * Get container by name
     *
     * @param   string $name
     * @return  mixed
     */
    public function get($name)
    {
        // TODO: NotFoundExceptionInterface
        return $this->has($name) ?? $this->getContainer($name);
    }

    /**
     * Container is exist
     *
     * @param   string $name
     * @return  bool
     */
    public function has($name): bool
    {
        // TODO: NotFoundExceptionInterface
        return isset($this->_containers[$name]);
    }
}
