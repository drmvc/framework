# DrMVC Framework

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/drmvc-framework/master/license.txt)

It's a minimalistic PHP 5.5 MVC framework.

## How to install

### Via composer

Stable release: `composer require drteam/drmvc-framework`

Unstable release: `composer require drteam/drmvc-framework "@dev"`

### Classic style

1. Download the [DrMVC framework](https://github.com/DrTeamRocks/drmvc-framework/releases) package

2. Extract the archive

## After install

* Initiate the styles and scripts, just run `bower install` from root directory

* Then edit config files in `Application/Configs` directory

## Where to get help

If you need help with this project, you can read detailed instruction on [Documentation](http://drmvc.com/docs/v1) page or read more about [API](http://drmvc.com/api/v1). 

If found the bug, please report about this on [GitHib issues](https://github.com/DrTeamRocks/drmvc-framework/issues) page.

## Some features

* The default database driver can work with most popular databases (eg. Mongo or PostgreSQL, or MySQL etc.)
* Simultaneous use of several localization files
* No need to describe each url-path into the routes, just use the url-templates, like page(/\<id\>)
 * You can push variables from the route directly into the controller
 * Only action_* (into your controller) functions can be executed dynamically
* Embedded variables cleaner method

## Available Plugins

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

## Contributors

* @PavelRykov paul@drteam.rocks

## What inspired and what alternatives

Created under the influence and have a some similar functionality.

* [Kohana](https://github.com/kohana/kohana) - Routing it is a very interesting part of this project

* [SimpleMVC](https://github.com/simple-mvc-framework/framework) - it was a very exciting project, before the author renamed this to Nova
