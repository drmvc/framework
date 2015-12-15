<?php

namespace Application\Controllers;

use System\Core\View;

class Index extends Main
{
    public function __construct()
    {
        parent::__construct();
    }

    public function action_index()
    {
        $data['title'] = $this->language->get('index');

        $data['styles_vendor'] = $this->styles_vendor;
        $data['scripts_vendor'] = $this->scripts_vendor;
        $data['styles'] = array();
        $data['scripts'] = array();

        $data['lng'] = $this->language;

        View::render('templates/header', $data);
        View::render('index', $data);
        View::render('templates/footer', $data);
    }

}
