<?php namespace DrMVC;

class Di implements Interfaces\DiInterface
{
    /**
     * Basic variables setter
     *
     * @param   string $name
     * @param   mixed $definition
     * @return  bool
     */
    public function set($name, $definition)
    {
        $this->$name = $definition;
        if (isset($this->$name)) return true;
        return false;
    }

    /**
     * Get container by name
     *
     * @param   string $name
     * @return  mixed
     */
    public function get($name)
    {
        if (isset($this->$name)) return $this->$name;
        return false;
    }

    /**
     * Check for container
     *
     * @param   string $name
     * @return  bool
     */
    public function has($name)
    {
        if (isset($this->$name)) return true;
        return false;
    }

    /**
     * Remove container from DI by name
     *
     * @param   string $name
     */
    public function remove($name)
    {
        if (isset($this->$name)) unset($this->$name);
    }
}
