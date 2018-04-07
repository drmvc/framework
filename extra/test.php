<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/configs/database.php', 'database');
$config->load(__DIR__ . '/configs/session.php', 'session');

$app = new \DrMVC\App($config);

$app
    ->get('', function() {
        echo 'get';
    })
    ->post('/zzz', function() {
        echo 'post';
    });

print_r($app);
