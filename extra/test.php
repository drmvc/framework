<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/configs/database.php', 'database');

$app = new \DrMVC\App($config);

$app
    ->get('', function() {
        print_r($this);
        echo 'get';
    })
    ->post('/zzz', function() {
        echo 'post';
    });

print_r($app);
