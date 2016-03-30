<?php namespace Application\Controllers;

use System\Core\View;

/**
 * Class Error
 * @package Application\Controllers
 */
class Error extends Main
{
    /**
     * Error constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function action_index()
    {
        $data['title'] = '404';

        $data['styles_vendor'] = $this->styles_vendor;
        $data['scripts_vendor'] = $this->scripts_vendor;
        $data['styles'] = $this->styles;
        $data['scripts'] = $this->scripts;

        View::render('templates/header', $data);
        View::render('error', $data);
        View::render('templates/footer', $data);
    }
}