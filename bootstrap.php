<?php namespace DrMVC\Core;

// Define the absolute paths for configured directories
define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('APPPATH', realpath($apppath) . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($apppath, $syspath);

// Application autoloader
spl_autoload_register(function ($name) {
    //echo "Want to load $name.<br/>\n";

    $pattern = array("/DrMVC\\\\App\\\\/ui", "/\\\\/ui");
    $field = array("", "/");
    $test_path = preg_replace($pattern, $field, $name);

    require_once APPPATH . $test_path . '.php';
    new $name();
});

// Default configurations
Config::load('config');

// Apply routes
Config::load('routes');
