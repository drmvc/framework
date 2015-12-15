<?php

namespace Modules\Database\Core\Drivers;

use Modules\Database\Core\Database;
use PDO;

class DSqlite extends Database
{

    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);
    }

    /**
     * PDO sqlite have some difference with another PDO drivers
     */
    public function connect()
    {
        if ($this->_connection)
            return;

        // Configurations
        $config = $this->_config;

        $this->_connection = new PDO('sqlite:' . $config['file']);
    }

    /**
     * Basic query function
     *
     * @param  string $sql
     * @param  null $mode fetch_all - get all rows, fetch - get first row, execute - just exec, count - lines count
     * @return object|string|bool
     */
    public function query($sql, $mode = NULL)
    {

        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = $sql;

        // Special street magic
        switch ($mode) {
            case 'fetch_all':
                $result = $this->_connection->query($sql)->fetchAll();
                break;
            case 'fetch':
                $result = $this->_connection->query($sql)->fetch();
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
                $result = $this->_connection->query($sql)->fetchAll();
                break;
        }

        return $result;
    }

}
