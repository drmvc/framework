<?php namespace Modules\Database\Core;

/**
 * Class Database for work with databases
 * @package Modules\Database\Core
 */

use System\Core\Config;

abstract class Database
{

    // Default database name
    public static $default = 'default';

    // For multiple instances
    public static $instances = array();

    // Instance name
    protected $_instance;

    // Raw server connection
    protected $_connection;

    // Configuration array
    protected $_config;

    // Configuration array
    protected $_last_query;

    /**
     * Get a singleton Database instance. If configuration is not specified,
     * it will be loaded from the database configuration file using the same
     * group as the name.
     *
     * @param null $name
     * @param array $config
     * @return mixed
     */
    public static function init($name = NULL, array $config = NULL)
    {
        if ($name === NULL) {
            // Use the default instance name
            $name = Database::$default;
        }

        if (!isset(Database::$instances[$name])) {
            if ($config === NULL) {
                // Load the configuration for this database
                $config = Config::load('database')[$name];
            }

            // Set the driver class name
            $driver = '\\Modules\\Database\\Core\\Drivers\\D' . ucfirst($config['type']);

            // Create the database connection instance
            $driver = new $driver($name, $config);

            // Store the database instance
            Database::$instances[$name] = $driver;

        }

        return Database::$instances[$name];
    }

    /**
     * Stores the database configuration locally and name the instance.
     * [!!] This method cannot be accessed directly, you must use [Database::init].
     *
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        // Set the instance name
        $this->_instance = $name;
        // Store the config locally
        $this->_config = $config;

        if (empty($this->_config['prefix'])) {
            $this->_config['prefix'] = '';
        }
    }

    /**
     * Return last query
     *
     * @return mixed
     */
    public function last_query()
    {
        return $this->_last_query;
    }

    /**
     * Disconnect from the database when the object is destroyed
     *
     * @return bool
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Connect to the database. This is called automatically when the first
     * query is executed.
     *
     * @return mixed
     */
    abstract public function connect();

    /**
     * Disconnect from the database. This is called automatically by [Database::__destruct].
     * Clears the database instance from [Database::$instances].
     *
     * @return bool
     */
    public function disconnect()
    {
        unset(Database::$instances[$this->_instance]);

        return true;
    }

}
