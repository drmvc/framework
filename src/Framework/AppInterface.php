<?php

namespace DrMVC\Framework;

use DrMVC\Router\MethodsInterface;

interface AppInterface extends MethodsInterface
{
    /**
     * Get all available containers
     *
     * @return  ContainersInterface
     */
    public function containers(): ContainersInterface;

    /**
     * Get custom container by name
     *
     * @param   string $name
     * @return  mixed
     */
    public function container(string $name);

}
