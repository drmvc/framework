<?php

namespace Modules\Database\Core\Drivers;

use Modules\Database\Core\Database;
use MongoClient;

class DMongo extends Database
{

    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);
    }

    /**
     * Connect via MongoClient driver
     */
    public function connect()
    {
        if ($this->_connection)
            return;

        // Configurations
        $config = $this->_config;

        // Connect to database
        $this->_connection = new MongoClient('mongodb://' . $config['username'] . ':' . $config['password'] . '@' . $config['hostname'] . ':' . $config['port'] . '/' . $config['database']);
    }

    // Basic query function
    public function query($collection_name, $mode = NULL)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = 'not supported';

        // Configurations
        $config = $this->_config;

        // Select collection by name in array
        $collection = $this->_connection->selectCollection($config['database'], $collection_name);

        return $collection;
    }

}
