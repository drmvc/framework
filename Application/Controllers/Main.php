<?php namespace Application\Controllers;

use System\Core\Controller;

/**
 * Class Main
 * @package Application\Controllers
 */
class Main extends Controller
{
    // Styles
    public $styles_vendor;
    public $styles;

    // Scripts
    public $scripts_vendor;
    public $scripts;

    /**
     * Main constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Vendor styles
        $this->styles_vendor = array(
            'bootstrap/dist/css/bootstrap.min.css',
        );
        // Site styles
        $this->styles = array();

        // Vendor scripts
        $this->scripts_vendor = array(
            'jquery/dist/jquery.min.js',
            'bootstrap/dist/js/bootstrap.min.js',
        );
        // Site scripts
        $this->scripts = array();

        // Include Application/Language/LANGUAGE_CODE/index.php file
        $this->language->load('index');
        // Include Application/Language/LANGUAGE_CODE/second.php file
        $this->language->load('second');
    }

}
