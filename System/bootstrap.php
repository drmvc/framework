<?php namespace System\Core;
/**
 * Initialize all
 */

// Autoload classes
require DOCROOT . 'autoload.php';

// Default configurations
$config = Config::load('config');
foreach ($config as $key => $value) define($key, $value);
unset ($config, $key, $value);

// Apply routes
Config::load('routes');
