<?php namespace Application\Controllers;

use System\Core\View;
use System\Core\Helpers\UUID;

// Get the example model
use Application\Models\Example as Model_Example;

/**
 * Class Index
 * @package Application\Controllers
 */
class Index extends Main
{
    /**
     * @var Model_Example
     */
    public $_example;

    /**
     * Index constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Create test class
        $this->_example = new Model_Example();
    }

    /**
     * Index page action
     */
    public function action_index()
    {
        // Store all data into array variable
        $this->view->data = array(
            // Page title
            'title' => $this->language->get('index'),

            // Add styles and scripts
            'styles_vendor' => $this->styles_vendor,
            'scripts_vendor' => $this->scripts_vendor,
            'styles' => $this->styles,
            'scripts' => $this->scripts,

            // Append the language
            'lng' => $this->language,

            // Generate the uuid
            'uuid' => UUID::v4()
        );

        // Render the templates
        $this->view->render('templates/header');
        $this->view->render('index');
        $this->view->render('templates/footer');
    }

}
