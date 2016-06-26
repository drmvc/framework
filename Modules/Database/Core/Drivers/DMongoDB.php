<?php namespace Modules\Database\Core\Drivers;

/**
 * Modern MongoDB php driver for mongo >=0.9.0
 * @package Modules\Database\Core\Drivers
 */

use Modules\Database\Core\Database;
use MongoDB\Driver\Manager as MongoManager;
use MongoDB\Driver\Command as MongoCommand;
use MongoDB\Driver\Exception as MongoException;
use MongoDB\Driver\Query as MongoQuery;

class DMongoDB extends Database
{

    /**
     * DMongoDB constructor
     *
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);
    }

    /**
     * Connect via MongoClient driver
     */
    public function connect()
    {
        if ($this->_connection) return;

        // Configurations
        $config = $this->_config;

        // Connect to database
        $this->_connection = new MongoManager('mongodb://' . $config['username'] . ':' . $config['password'] . '@' . $config['hostname'] . ':' . $config['port'] . '/' . $config['database']);
    }

    /**
     * Basic command function
     *
     * @param $query - Should be like new MongoDB\Driver\Query($filter, $options);
     * @return mixed
     */
    public function command($query)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = 'not supported';

        // Configurations
        $config = $this->_config;

        // Create command from query
        $command = new MongoCommand($query);

        try {
            $cursor = $this->_connection->executeCommand($config['database'], $command);
            $response = $cursor->toArray();
        } catch (\MongoException $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }

    public function query($collection, $filter, $options)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = 'not supported';

        // Configurations
        $config = $this->_config;

        // Create command from query
        $query = new MongoQuery($filter, $options);

        try {
            $cursor = $this->_connection->executeQuery($config['database'] . '.' . $collection, $query);
            $response = $cursor->toArray();
        } catch (MongoException $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }
}
