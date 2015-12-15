<?php

namespace Application\Controllers;

use System\Core\Controller;

class Main extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->styles_vendor = array(
                'bootstrap/dist/css/bootstrap.min.css',
        );

        $this->scripts_vendor = array(
                'jquery/dist/jquery.min.js',
                'bootstrap/dist/js/bootstrap.min.js',
                'holderjs/holder.min.js',
        );

        $this->language->load('main');
    }

}
