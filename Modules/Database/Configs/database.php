<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default' => array(
		// Database type
		'type'     => 'pdo',
		// Database default PDO driver [pgsql/mysql/oci8/odbc]
		'driver'   => 'pgsql',
		// Network configuration
		'hostname' => 'localhost',
		'port'     => '5432',
		// Set the database name and user credentials
		'database' => 'test1',
		'prefix'   => '',
		'username' => 'test',
		'password' => 'testp',
		// Client encoding
		'encoding' => 'utf8',
	),
	'test_sqlite' => array(
		// Database type (PDO based)
		'type'     => 'sqlite',
		// Path to sqlite file
		'file'     => APPPATH . '/test_sqlite_local.db',
	),
	'jabber_openfire' => array(
		// Database type
		'type'     => 'pdo',
		// Database default PDO driver [pgsql/mysql/oci8/odbc]
		'driver'   => 'mysql',
		// Network configuration
		'hostname' => 'localhost',
		'port'     => '5432',
		// Set the database name and user credentials
		'database' => 'test2',
		'prefix'   => '',
		'username' => 'test',
		'password' => 'testp'
		// Client encoding
		'encoding' => 'utf8',
	),
	'fastbase_for_stats' => array(
		// Database type
		'type'     => 'mongo',
		// Network configuration
		'hostname' => 'localhost',
		'port'     => '5432',
		// Set the database name and user credentials
		'database' => 'test3',
		'prefix'   => '',
		'username' => 'test',
		'password' => 'testp'
	),
);
