# DrMVC Framework

[![Latest Stable Version](https://poser.pugx.org/drmvc/framework/v/stable)](https://packagist.org/packages/drmvc/framework)
[![Build Status](https://travis-ci.org/drmvc/framework.svg?branch=master)](https://travis-ci.org/drmvc/framework)
[![Total Downloads](https://poser.pugx.org/drmvc/framework/downloads)](https://packagist.org/packages/drmvc/framework)
[![License](https://poser.pugx.org/drmvc/framework/license)](https://packagist.org/packages/drmvc/framework)
[![PHP 7 ready](https://php7ready.timesplinter.ch/drmvc/framework/master/badge.svg)](https://travis-ci.org/drmvc/framework)

It's a minimalistic PHP5 and PHP7 MVC framework with dynamic routing and any databases what support your hosting.

You can work simultaneously with multiple databases, for example with MySQL and MongoDB from one controller, or with MSSQL and MySQL via same model class.

## Some features

* The default database driver can work with most popular databases (eg. Mongo or PostgreSQL, or MySQL etc.)
* Simultaneous use of several localization files
* Multiple templates support
* No need to describe each url-path into the routes, just use the url-templates, like "page(/\<id\>)"
 * You can push variables from the route directly into the controller
 * Only action_* (into your controller) functions can be executed dynamically
* Embedded variables cleaner method
* Slug-url generator into URL class

## How in works

You can look at the special [demo application](https://github.com/drmvc/demo) if you are interested in details.

### Project structure

Here is a simple example of the directory structure:

    app/
     |-Configs/
        |-configs.php
        |-routes.php
     |-Controllers/
     |-Language/
     |-Models/
     |-Views/
    public/
     |-index.php
     |-css/
     |-js/
     |-vendor/ (libs downloaded via bower)
    vendor/ (libs downloaded via composer)

### Example of `index.php` file

    <?php
    // Enable autoloader
    include __DIR__ . "/../vendor/autoload.php";
    // Set path of the application directory
    define('APPPATH', __DIR__ . '/../app/');
    // Default configurations
    DrMVC\Core\Config::load('config');
    // Apply routes
    DrMVC\Core\Config::load('routes');
    // Start session
    DrMVC\Core\Session::init();
    // Render current page
    DrMVC\Core\Request::factory(true)->execute()->render();

### Example of `config.php` file

    <?php defined('APPPATH') OR die('No direct script access.');
    define('SITETITLE', 'DrMVC');
    define('URL', 'http://drmvc');
    define('DIR', '/');
    ...

### Example of `routes.php` file

    <?php namespace DrMVC\Core;
    // Custom error page
    Route::set('error', 'error')
        ->defaults(array('controller' => 'Error', 'action' => 'index'));
    // Default workmode
    Route::set('default', '(<controller>(/<action>(/<id>)))')
        ->defaults(array('controller' => 'Index', 'action' => 'index'));

## How to install

### Via composer

    composer require drmvc/framework

### Classic style

* Download the [DrMVC Framework](https://github.com/drmvc/framework/releases) package
* Extract the archive
* Initiate the scripts, just run `composer update` from directory with sources

## Where to get help

If you need help with this project, you can read detailed instruction on [Documentation](https://drmvc.com/docs) page. 

If you found the bug, please report about this on [GitHub Issues](https://github.com/drmvc/framework/issues) page.

## Developers

* [Paul Rock](https://github.com/EvilFreelancer)

## What inspired and what alternatives

Created under the influence and have a some similar functionality.

* [Kohana](https://github.com/kohana/kohana) - Routing it is a very interesting part of this project
* [SimpleMVC](https://github.com/simple-mvc-framework/framework) - it was a very exciting project, before the author renamed this to Nova
* [Slim](https://github.com/slimphp/Slim) - Is a PHP micro framework, I really like how there are implemented the PSR-4 conception.
