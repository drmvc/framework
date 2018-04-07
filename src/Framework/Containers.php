<?php

namespace DrMVC\Framework;

use DrMVC\Config\ConfigInterface;

class Containers implements ContainersInterface
{
    /**
     * @var array
     */
    private $_containers = [];

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
     * @return  ContainersInterface
     */
    private function setContainer(string $name, $object): ContainersInterface
    {
        $this->_containers[$name] = $object;
        return $this;
    }

    /**
     * PSR-11 set container
     *
     * @param   string $container
     * @param   string|object $object
     * @param   ConfigInterface $config
     * @return  ContainersInterface
     */
    public function set(string $container, $object, ConfigInterface $config = null): ContainersInterface
    {
        if (\is_object($object)) {
            $this->setContainer($container, $object);
        } else {
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
        return $this->has($name) ? $this->getContainer($name) : null;
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
