<?php namespace Modules\Database\Core\Drivers;

/**
 * Extended PDO class for work with SQLite driver
 * @package Modules\Database\Core\Drivers
 */

use Modules\Database\Core\Database;
use PDO;

class DSqlite extends Database
{
    /**
     * DSqlite constructor
     *
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);

        if (!$this->_connection) $this->connect();
    }

    /**
     * PDO sqlite have some difference with another PDO drivers
     */
    public function connect()
    {
        if ($this->_connection) return;

        // Configurations
        $config = $this->_config;

        $this->_connection = new PDO('sqlite:' . $config['file']);
    }

    /**
     * Run a select statement against the database
     *
     * @param  string $query
     * @param  array  $array parameter into named array
     * @return array
     */
    public function select($query, $array = array())
    {
        $statement = $this->_connection->prepare($query);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $statement->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $statement->bindValue("$key", $value);
            }
        }

        // Execute the Statement.
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Exec query without return, create table for example
     *
     * @param $query
     * @return mixed
     */
    public function exec($query)
    {
        return $this->_connection->exec($query);
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
        error_log("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)", 0);

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


    /**
     * Delete rows from database
     *
     * @param $table
     * @param $where
     * @param int $limit
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = NULL;
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

        $statement = $this->_connection->prepare("DELETE FROM $table WHERE $whereDetails");
        error_log("DELETE FROM $table WHERE $whereDetails", 0);

        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        $statement->execute();
    }

    /**
     * Clean table function
     *
     * @param $table
     * @return mixed
     */
    public function truncate($table)
    {
        return $this->exec("TRUNCATE TABLE $table");
    }

}
