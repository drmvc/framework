<?php namespace DrMVC\Interfaces;

interface DiInterface
{
    /**
     * Registers a service in the services container
     *
     * @param   string $name
     * @param   mixed $definition
     * @return  bool
     */
    public function set($name, $definition);

    /**
     * Resolves the service based on it's name
     *
     * @param   string $name
     * @return  mixed
     */
    public function get($name);

    /**
     * Check whether the DI contains a service by a name
     *
     * @param   string $name
     * @return  bool
     */
    public function has($name);

    /**
     * Removes a service in the services container
     *
     * @param   string $name
     */
    public function remove($name);
}
