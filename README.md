# DrMVC Framework

[![Latest Stable Version](https://poser.pugx.org/drmvc/framework/v/stable)](https://packagist.org/packages/drmvc/framework)
[![Build Status](https://travis-ci.org/drmvc/framework.svg?branch=master)](https://travis-ci.org/drmvc/framework)
[![Total Downloads](https://poser.pugx.org/drmvc/framework/downloads)](https://packagist.org/packages/drmvc/framework)
[![License](https://poser.pugx.org/drmvc/framework/license)](https://packagist.org/packages/drmvc/framework)
[![PHP 7 ready](https://php7ready.timesplinter.ch/drmvc/framework/master/badge.svg)](https://travis-ci.org/drmvc/framework)

It's a minimalistic PHP5 and PHP7 MVC framework with dynamic routing and any databases what support your hosting.

You can work simultaneously with multiple databases, for example with MySQL and MongoDB from one controller, or with MSSQL and MySQL via same model class.

    composer require drmvc/framework

## Some features

* Strong MVC conception
* The default database driver can work with most popular databases (eg. Mongo or PostgreSQL, or MySQL etc.)
* Simple and clean ORM for work with your models
* Simultaneous use of several localization files
* Multiple templates support any template engines supported 
* No need to describe each url-path into the routes, just use the url-templates, like "page(/\<id\>)"
 * You can push variables from the route directly into the controller
 * Only functions which name starts from `action_` can be executed dynamically
* Embedded variables cleaner method
* Slug-url generator into URL class

## How it works

You can look at the special [demo application](https://github.com/drmvc/app) if you are interested in details.

### Project structure

Here is a simple example of the directory structure:

```
app/
    |-Configs/
        |-configs.php
        |-routes.php
    |-Controllers/
    |-Models/
    |-Views/
public/
    |-**index.php**
    |-css/
    |-js/
    |-vendor/ (bower libs)
vendor/ (composer libs)
```

### Example of `index.php` file

```php
<?php
include __DIR__ . "/../vendor/autoload.php";

// Set path of the application directory
define('APPPATH', __DIR__ . '/../app/');

// Load files with configuration from Configs directory
DrMVC\Core\Config::load('config');
DrMVC\Core\Config::load('routes');

// Enable the server side session
DrMVC\Core\Session::init();

// Run the application
DrMVC\Core\Request::factory(true)->execute()->render();
```

### Example of `config.php` file

```php
<?php defined('APPPATH') OR die('No direct script access.');

/**
 * Site title
 */
define('SITETITLE', 'DrMVC Framework');

/**
 * Site main URL (optional)
 */
define('URL', 'http://drmvc');

/**
 * Site path relative to the URL
 */
define('DIR', '/');

/**
 * Server side session prefix
 */
define('SESSION_PREFIX', 'drmvc_');

/**
 * Default language code
 */
define('LANGUAGE_CODE', 'en');

/**
 * Theme name
 */
define('THEME', 'default');

/**
 * Generate sitemap.xml
 */
define('SITEMAP_ENABLED', true);
```

### Example of `routes.php` file

```php
<?php namespace DrMVC\Core;

/**
 * If we open page http://example.com/error or wrong path.
 * Please, do not change this route name.
 */
Route::set('error', 'error')
    ->defaults(array(
        'controller' => 'Error',
        'action' => 'index',
    ));

/**
 * If sitemap generator enabled
 */
if (SITEMAP_ENABLED === true) {
    /**
     * Generate sitemap from available actions
     * Default url is http://example.com/sitemap.xml
     */
    Route::set('sitemap', 'sitemap.xml')
        ->defaults(array(
            'controller' => 'Sitemap',
            'action' => 'index',
        ));
}

/**
 * Default route:
 * <controller> - application controller name
 * <action>     - "action_*" from controller
 * <id>         - dynamical variable, you can get this via $this->request->param()
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'Index',
        'action' => 'index',
    ));

```

## Where to get help

If you need help with this project, you can read detailed instruction on [Documentation](https://drmvc.com/docs) page. 

If you found the bug, please report about this on [GitHub Issues](https://github.com/drmvc/framework/issues) page.

## Developers

* [Paul Rock](https://github.com/EvilFreelancer)

## What inspired and what alternatives

Created under the influence and have a some similar functionality.

* [Phalcon](https://github.com/phalcon) - Simple and clean code of your application
* [Kohana](https://github.com/kohana/kohana) - Routing it is a very interesting part of this project
* [SimpleMVC](https://github.com/simple-mvc-framework/framework) - it was a very exciting project, before the author renamed this to Nova
* [Slim](https://github.com/slimphp/Slim) - Is a PHP micro framework, I really like how there are implemented the PSR-4 conception.
