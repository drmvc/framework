<?php namespace DrMVC\Core\View\Engine;

/**
 * Adapter to use PHP itself as template engine
 */
class Php
{
    /**
     * Renders a view without engine (pure php)
     *
     * @param   string $file - Path to file
     * @param   array $params - Parameters available from view
     * @return  mixed
     */
    public function render($file, $params)
    {
        return file_exists($file) ? include($file) : false;
    }
}
