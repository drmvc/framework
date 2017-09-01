<?php namespace DrMVC\Di;

interface LazyInterface
{
    /**
     * Invokes the Lazy to return a result, usually an object.
     *
     * @return mixed
     */
    public function __invoke();
}
