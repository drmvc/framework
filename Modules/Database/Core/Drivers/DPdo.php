<?php

namespace Modules\Database\Core\Drivers;

use Modules\Database\Core\Database;
use PDO;

class DPdo extends Database
{
    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);
    }

    /**
     * Connect via PDO driver
     */
    public function connect()
    {
        if ($this->_connection)
            return;

        // Configurations
        $config = $this->_config;
        // Force PDO to use exceptions for all errors
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        // Connection string
        $dsn = $config['driver'] . ":host=" . $config['hostname'] . ";port=" . $config['port'] . ";dbname=" . $config['database'];

        //echo "$dsn, $config[username], $config[password]";
        $this->_connection = new PDO($dsn, $config['username'], $config['password'], $options);
    }

    /**
     * Basic query function
     *
     * @param  string $sql
     * @param  null   $mode fetch_all - get all rows, fetch - get first row, execute - just exec, count - lines count
     * @return object|string|bool
     */
    public function query($sql, $mode = NULL)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Enable encoding if not empty
        if (!empty($this->_config['encoding'])) {
            // Set client encoding
            $this->_connection->prepare("SET CLIENT_ENCODING TO '" . $this->_config['encoding'] . "'");
            // Set namespace encoding
            $this->_connection->prepare("SET NAMES '" . $this->_config['encoding'] . "'");
        }

        // Set the last query
        $this->_last_query = $sql;

        // Street magic
        switch ($mode) {
            case 'fetch_all':
                $result = $this->_connection->query($sql)->fetchAll(PDO::FETCH_OBJ);
                break;
            case 'fetch':
                $result = $this->_connection->query($sql)->fetch(PDO::FETCH_OBJ);
                break;
            case 'execute':
                $this->_connection->query($sql);
                $result = true;
                break;
            case 'count':
                $result = $this->_connection->query($sql)->rowCount();
                break;
            // By default - fetch_all
            default:
                $result = $this->_connection->query($sql)->fetchAll(PDO::FETCH_OBJ);
                break;
        }

        return $result;
    }

}
