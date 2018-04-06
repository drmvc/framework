<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/configs/database.php', 'database');
$config->load(__DIR__ . '/configs/session.php', 'session');

$app = new \DrMVC\App($config);

print_r($app);
