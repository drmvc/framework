# DrMVC Framework

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/drmvc-framework/master/license.txt)

It's a minimalistic PHP 5.5 MVC framework, created under the influence of [Kohana](https://github.com/kohana/kohana) and [SimpleMVC](https://github.com/simple-mvc-framework/framework), and have a some similar functionality.

## Some features

* The default database driver can work with most popular databases (eg. Mongo or PostgreSQL, or MySQL etc.)
* Simultaneous use of several localization files
* No need to describe each url-path into the routes, just use the url-templates, like page(/\<id\>)
 * You can push variables from the route directly into the controller
 * Only action_* (into your controller) functions can be executed dynamically
* Embedded variables cleaner method

## Modules

* **Database** - default database module, supports:
 * Multiple database instances
 * PDO databases like MySQL, or PostgreSQL
 * Mongo through MongoClient and MongoDB driver
 * SQLite (Via PDO driver, but have some fix for correct work)

* **DatabaseSimple** - ported from SimpleMVC `Helpers\Database` class
 * Use single database instance
 * PDO databases like MySQL, or PostgreSQL

* [**PHPMailer**](https://github.com/PHPMailer/PHPMailer) - awesome mailer class for your site

* [**PHPZabbixAPI**](https://github.com/confirm/PhpZabbixApi) - class for integration with Zabbix

## How routing works

Default routes stored in:

* `Application/Configs/routes.php`

But if you need set the some system routes, you can create file:

* `System/Configs/routes.php`

Routes by default:

```php
Route::set('error', 'error')
    ->defaults(array(
        'controller' => 'Error',
        'action' => 'index',
    ));

Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'Index',
        'action' => 'index',
    ));
```

If you need add new routes, just edit the `routes.php` file, for example:

```php
$route_name = 'main';
$route_url_with_regexp = 'main/example';

Route::set($route_name, $route_url_with_regexp)
 ->defaults(array('controller' => 'Main', 'action' => 'dashboard' ));
```

In this example route call the `Countroller\Main::action_dashboard()`, from `Application/Controllers` directory if we open the http://yoursite.com/main/example

## How to install

### Via composer

`composer require drteam/drmvc-framework "@dev"`

### Classic style

1. Download the DrMVC framework package

2. Extract the archive, if needed

3. Then edit config files in `Application/Configs` directory
