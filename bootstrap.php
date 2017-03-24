<?php namespace DrMVC\Core;

// Define the absolute paths for configured directories
define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('APPPATH', realpath($apppath) . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($syspath) . DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($apppath, $syspath);

$directory = new \RecursiveDirectoryIterator(APPPATH);
$iterator = new \RecursiveIteratorIterator($directory);
foreach ($iterator as $info) {
    $file = $info->getPathname();
    $apppath = preg_replace('/\//','\\\/',APPPATH);

    if (
        preg_match('/^.+\.php$/ui', $file) &&
        preg_match('/^(' . $apppath . 'Controllers|' . $apppath . 'Models)/ui', $file)
    ) {
//        echo $file . "\n";
        require_once $file;
    }
}

print_r(get_declared_classes());

// Default configurations
$config = Config::load('config');
foreach ($config as $key => $value) define($key, $value);
unset ($config, $key, $value);

// Apply routes
Config::load('routes');
