<?php

namespace Application\Controllers;

use System\Core\View;

class Error extends Main
{
    public function __construct()
    {
        parent::__construct();
    }

    public function action_index()
    {
        $data['title'] = '404';

        $data['styles_vendor'] = $this->styles_vendor;
        $data['scripts_vendor'] = $this->scripts_vendor;
        $data['styles'] = array();
        $data['scripts'] = array();

        View::render('templates/header', $data);
        View::render('error', $data);
        View::render('templates/footer', $data);
    }
}