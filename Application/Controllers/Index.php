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
        // Page info
        $data['title'] = $this->language->get('index', 'index');

        // Add styles and scripts
        $data['styles_vendor'] = $this->styles_vendor;
        $data['scripts_vendor'] = $this->scripts_vendor;
        $data['styles'] = $this->styles;
        $data['scripts'] = $this->scripts;

        // Get the main language object
        $data['lng'] = $this->language;

        // Generate the uuid
        $data['uuid'] = UUID::v4();

        View::render('templates/header', $data);
        View::render('index', $data);
        View::render('templates/footer', $data);
    }

}
