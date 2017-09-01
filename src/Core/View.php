<?php namespace DrMVC\Core;

/**
 * Class View for work with views
 */
class View
{
    /**
     * Folder with views
     * @var string
     */
    protected $_viewsDir;

    /**
     * Extentions of files
     * @var array
     */
    protected $_engines;

    /**
     * Data from controllers
     * @var array $data
     */
    public $data;

    /**
     * View class constructor
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Set folder with views
     *
     * @param   string $path
     */
    public function setViewsDir($path)
    {
        $this->_viewsDir = $path;
    }

    /**
     * Return a path of views
     *
     * @return  string
     */
    public function getViewsDir()
    {
        return $this->_viewsDir;
    }

    /**
     * Register new engies
     *
     * @param   array $engines
     */
    public function registerEngines(array $engines = array())
    {
        foreach ($engines as $key => $value) {
            if ($value instanceof \Closure) $this->_engines[$key] = $value();
            else $this->_engines[$key] = $value;
        }
    }

    /**
     * Include layout file
     *
     * @param string $path
     * @param bool $data
     * @return mixed|null
     */
    public function render($path, $data = false)
    {
        if ($data === false) $data = $this->data;

        // Application view
        $appfile = APPPATH . 'Views' . DIRECTORY_SEPARATOR . THEME . DIRECTORY_SEPARATOR . $path . '.php';

        switch (true) {
            case file_exists($appfile):
                $file = include($appfile);
                break;
            default:
                $file = NULL;
                break;
        }

        return $file;
    }

}
