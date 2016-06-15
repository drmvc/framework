<?php

namespace Modules\Database\Core\Drivers;

use Modules\Database\Core\Database;
use PDO;

class DPdo extends Database
{
    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);

        if (!$this->_connection) $this->connect();
    }

    /**
     * Connect via PDO driver
     */
    public function connect()
    {
        if ($this->_connection) return;

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
     * Run a select statement against the database
     *
     * @param  string $query
     * @return array
     */
    public function select($query)
    {
        $statement = $this->_connection->prepare($query);

        // Execute the Statement.
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Insert method
     *
     * @param  string $table table name
     * @param  array $data array of columns and values
     * @return string
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $statement = $this->_connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();
        return $this->_connection->lastInsertId();
    }

    /**
     * Update method
     *
     * @param  string $table table name
     * @param  array $data array of columns and values
     * @param  array $where array of columns and values
     * @return string
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $statement = $this->_connection->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");
        error_log("UPDATE $table SET $fieldDetails WHERE $whereDetails", 0);

        foreach ($data as $key => $value) {
            $statement->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        $statement->execute();
        return $statement->rowCount();
    }

}
