# DrMVC Framework

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/drmvc-framework/master/license.txt)

It's a minimalistic PHP5 and PHP7 MVC framework with dynamic routing and any databases what support your hosting.

You can work simultaneously with multiple databases, for example with MySQL and MongoDB from one controller, or with MSSQL and MySQL via same model class.

## How to install

### Via composer

* Stable release: `composer require drteam/drmvc-framework`

* Unstable release: `composer require drteam/drmvc-framework "@dev"`

### Classic style

* Download the [DrMVC framework](https://github.com/drmvc/framework/releases) package

* Extract the archive

## After install

* Initiate the styles and scripts, just run `bower install` from root directory

* Then edit config files in `Application/Configs` directory

## Where to get help

If you need help with this project, you can read detailed instruction on [Documentation](http://drmvc.com/docs/v1) page or read more about [API](http://drmvc.com/api/v1). 

If you found the bug, please report about this on [GitHub Issues](https://github.com/DrTeamRocks/drmvc-framework/issues) page.

## Some features

* The default database driver can work with most popular databases (eg. Mongo or PostgreSQL, or MySQL etc.)
* Simultaneous use of several localization files
* Multiple templates support
* No need to describe each url-path into the routes, just use the url-templates, like "page(/\<id\>)"
 * You can push variables from the route directly into the controller
 * Only action_* (into your controller) functions can be executed dynamically
* Embedded variables cleaner method
* Slug-url generator into URL class

## Developers

* [Paul Rock](https://github.com/EvilFreelancer)

## What inspired and what alternatives

Created under the influence and have a some similar functionality.

* [Kohana](https://github.com/kohana/kohana) - Routing it is a very interesting part of this project

* [SimpleMVC](https://github.com/simple-mvc-framework/framework) - it was a very exciting project, before the author renamed this to Nova
