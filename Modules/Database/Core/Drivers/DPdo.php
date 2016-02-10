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

    /**
     * Insert method
     *
     * @param  $table table name
     * @param  $data  array of columns and values
     * @return string
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $stmt = $this->_connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
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

        $stmt = $this->_connection->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");
        error_log("UPDATE $table SET $fieldDetails WHERE $whereDetails", 0);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

}
