# DrMVC Framework

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/drmvc-framework/master/license.txt)

It's a minimalistic PHP 5.5 MVC framework, created under the influence of [Kohana](https://github.com/kohana/kohana) and [SimpleMVC](https://github.com/simple-mvc-framework/framework), and have a some similar functionality.

## Modules

* **Database** - default database module, supports:
 * Multiple database instances
 * PDO databases like MySQL, or PostgreSQL
 * Mongo
 * SQLite (Via PDO driver, but have some fix for correct work)

* **DatabaseSimple** - ported from SimpleMVC `Helpers\Database` class
 * Use single database instance
 * PDO databases like MySQL, or PostgreSQL

* [**PHPMailer**](https://github.com/PHPMailer/PHPMailer) - awesome mailer class for your site

## How to install

1. Download the DrMVC framework package

2. Extract the archive, if needed

3. Then edit config files:

 3.1. Application/Configs/configs.php

 3.2. Application/Configs/routes.php
