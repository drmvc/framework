<?php namespace DrMVC;

class Di implements Interfaces\DiInterface
{
    /**
     * Array of dependencies
     * @var array
     */
    protected $dependencies = array();

    /**
     * Basic variables setter
     *
     * @param   string $name
     * @param   mixed $definition
     * @return  bool
     */
    public function set($name, $definition)
    {
        if ($definition instanceof \Closure) $definition = new Di\Lazy($definition);

        $this->dependencies[$name] = $definition;

        return $this->has($name);
    }

    /**
     * Get container by name
     *
     * @param   string $name
     * @return  mixed
     */
    public function get($name)
    {
        return isset($this->dependencies[$name]) ? $this->dependencies[$name] : false;
    }

    /**
     * Check for container
     *
     * @param   string $name
     * @return  bool
     */
    public function has($name)
    {
        return isset($this->dependencies[$name]) ? true : false;
    }

    /**
     * Remove container from dependencies by name
     *
     * @param   string $name
     */
    public function remove($name)
    {
        if (isset($this->dependencies[$name])) unset($this->dependencies[$name]);
    }
}
