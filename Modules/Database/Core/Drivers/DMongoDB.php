<?php namespace Modules\Database\Core\Drivers;

/**
 * New Mongo php driver for mongo >=0.9.0
 */

use Modules\Database\Core\Database;

use MongoDB\Driver\Manager as MongoManager;
use MongoDB\Driver\Command as MongoCommand;
use MongoDB\Driver\Exception as MongoException;
use MongoDB\Driver\Query as MongoQuery;

class DMongoDB extends Database
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
        if ($this->_connection) return;

        // Configurations
        $config = $this->_config;

        // Connect to database
        $this->_connection = new MongoManager('mongodb://' . $config['username'] . ':' . $config['password'] . '@' . $config['hostname'] . ':' . $config['port'] . '/' . $config['database']);
    }
    /**
     * Basic command function
     *
     * @param $data - Should be like new MongoDB\Driver\Query($filter, $options);
     * @return mixed
     */
    public function command($data)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = $data;

        // Configurations
        $config = $this->_config;

        // Create command from query
        $command = new MongoCommand($data);

        try {
            $cursor = $this->_connection->executeCommand($config['database'], $command);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }

    public function write($collection, $command, $data)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = $data;

        // Configurations
        $config = $this->_config;

        // Exec bulk command
        $bulk = new \MongoDB\Driver\BulkWrite;

        switch($command) {
            case 'insert':
                $data['_id'] = new \MongoDB\BSON\ObjectID;
                $bulk->$command($data);
                break;
            case 'update';
                $bulk->$command($data[0], $data[1], $data[2]);
                break;
            case 'delete';
                $bulk->$command($data[0], $data[1]);
                break;
        }

        try {
            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $response = $this->_connection->executeBulkWrite($config['database'] . '.' . $collection, $bulk, $writeConcern);
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            //print_r($e);die();
            echo $e->getMessage(), "\n";
            //exit;
        }

        return $response;
    }

    public function query($collection, $filter, $options)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = array($filter, $options);

        // Configurations
        $config = $this->_config;

        // Create command from query
        $query = new MongoQuery($filter, $options);

        try {
            $cursor = $this->_connection->executeQuery($config['database'] . '.' . $collection, $query);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }
}
