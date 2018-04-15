[![Latest Stable Version](https://poser.pugx.org/drmvc/framework/v/stable)](https://packagist.org/packages/drmvc/framework)
[![Build Status](https://travis-ci.org/drmvc/framework.svg?branch=master)](https://travis-ci.org/drmvc/framework)
[![Total Downloads](https://poser.pugx.org/drmvc/framework/downloads)](https://packagist.org/packages/drmvc/framework)
[![License](https://poser.pugx.org/drmvc/framework/license)](https://packagist.org/packages/drmvc/framework)
[![PHP 7 ready](https://php7ready.timesplinter.ch/drmvc/framework/master/badge.svg)](https://travis-ci.org/drmvc/framework)
[![Code Climate](https://codeclimate.com/github/drmvc/framework/badges/gpa.svg)](https://codeclimate.com/github/drmvc/framework)
[![Scrutinizer CQ](https://scrutinizer-ci.com/g/drmvc/framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drmvc/framework/)

# DrMVC Framework

A framework that combines some modules. necessary to create a full-fledged web application.

    composer require drmvc/framework

## How to use

More examples you can find [here](extra).

Example of `index.php`:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/../app/database.php', 'database');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \DrMVC\App($config);
$app
    ->get('/', \MyApp\Controllers\Index::class . ':default') //-> public function action_default()
    ->get('/zzz', \MyApp\Controllers\Index::class) //-> public function action_index()
    ->get('/zzz/<action>', \MyApp\Controllers\Index::class)
    ->get('/aaa', function(Request $request, Response $response, $args) {
        print_r($args);
    });

echo $app->run();
```

Example of controller:

```php
<?php

namespace MyApp\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Index
{
    public function action_index(Request $request, Response $response, $args)
    {
        $out = [
            'dummy',
            'array'
        ];

        $json = json_encode($out);
        header('Content-Type: application/json');
        $response->getBody()->write($json);
    }

    public function action_defaultRequest $request, Response $response, $args)
    {
        $out = [
            'test1',
            'test2'
        ];

        $json = json_encode($out);
        header('Content-Type: application/json');
        $response->getBody()->write($json);
    }
}
```

## Where to get help

If you need help with this project, you can read detailed instruction on [Documentation](https://drmvc.com/docs) page. 

If you found the bug, please report about this on [GitHub Issues](https://github.com/drmvc/framework/issues) page.

## About PHP Unit Tests

First need to install all dev dependencies via `composer update`, then
you can run tests by hands from source directory via `./vendor/bin/phpunit` command.

# Links

* [DrMVC Framework](https://drmvc.com)
* [Slim](https://github.com/slimphp/Slim) - Is a PHP micro framework, I really like how there are implemented the PSR-4 conception.
* [SimpleMVC](https://github.com/simple-mvc-framework/framework) - it was a very exciting project, before the author renamed this to Nova
* [Phalcon](https://github.com/phalcon) - Simple and clean code of your application
