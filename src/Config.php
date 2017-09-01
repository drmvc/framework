<?php namespace DrMVC;

/**
 * Class Config
 * @package DrMVC
 */
class Config
{
    /**
     * Config constructor.
     *
     * @param   array $parameters
     * @return  mixed
     */
    public function __construct(array $parameters)
    {
        /**
         * Parse array of parameters and store as class attributes
         */
        foreach ($parameters as $key => $value) $this->$key = $value;

        /**
         * Return object of current class
         */
        return $this;
    }
}
