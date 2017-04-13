<?php namespace DrMVC\Core;

// Simple check for application directory
if (!isset($apppath)) $apppath = __DIR__;

// Define the absolute paths for configured directories
define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('APPPATH', realpath($apppath) . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($apppath, $syspath);

// Application autoloader
spl_autoload_register(function ($name) {
    $pattern = array("/DrMVC\\\\App\\\\/ui", "/\\\\/ui");
    $field = array("", "/");
    $test_path = preg_replace($pattern, $field, $name);
    $path = APPPATH . $test_path . '.php';

    try {
        // If controller is not exist
        if (!file_exists($path)) {
            throw new \Exception ($path . ' does not exist');
        } else {
            require_once $path;
            new $name();
        }
    } catch (\Exception $e) {
        // Get details about error controller
        $error = Route::get('error')->defaults();
        $controller = Request::$prefix . $error['controller'];
        $action = 'action_' . $error['action'];
        // Create the app
        $app = new $controller();
        $app->_error = $e->getMessage();
        $app->$action();
    }
});

// Default configurations
Config::load('config');

// Apply routes
Config::load('routes');
