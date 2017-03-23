<?php namespace DrMVC\Core;

/**
 * Core controller, all other controllers extend this base controller
 * @package System\Core
 */
abstract class Controller
{
    /**
     * View variable to use the view class
     *
     * @var string
     */
    public $view;

    /**
     * Language variable to use the languages class
     *
     * @var string
     */
    public $language;

    /**
     * On run make an instance of the config class and view class
     */
    public function __construct()
    {
        /**
         * initialise the views object
         */
        $this->view = new View();

        /**
         * initialise the language object
         */
        $this->language = new Language();
    }

}
