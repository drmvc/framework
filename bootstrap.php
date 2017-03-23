<?php namespace DrMVC\Core;
/**
 * Initialize all
 */

// Default configurations
$config = Config::load('config');
foreach ($config as $key => $value) define($key, $value);
unset ($config, $key, $value);

// Apply routes
Config::load('routes');
