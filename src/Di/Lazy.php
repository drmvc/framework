<?php namespace DrMVC\Di;

/**
 * Class Lazy
 * @package DrMVC\Di
 */
class Lazy
{
    /**
     * A callable to create an object instance.
     * @var callable
     */
    protected $callable;

    /**
     * Lazy constructor.
     *
     * @param   callable $callable - A callable to create an object instance.
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Invokes the closure to create the instance.
     *
     * @return  object - The object created by the closure.
     */
    public function __invoke()
    {
        $callable = $this->callable;
        return $callable();
    }
}
