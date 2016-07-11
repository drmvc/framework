<?php namespace Modules\Database\Core\Drivers;

/**
 * Class for work with PDO drivers
 * @package Modules\Database\Core\Drivers
 */

use Modules\Database\Core\Database;
use PDO;
use PDOException;

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
     * @param  array  $array parameter into named array
     * @return array
     */
    public function select($query, $array = array())
    {
        $statement = $this->prepare($query);
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
     * @param  array  $data array of columns and values
     * @param  null   $return_id id name if need return
     * @return string
     */
    public function insert($table, $data, $return_id = null)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $statement = $this->_connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        try {
            $this->_connection->beginTransaction();
            $statement->execute();
            $this->_connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->_connection->rollback();
            return "Error!: " . $e->getMessage() . "</br>";
        }
    }

    /**
     * Get last inserted id from special table
     *
     * @param $table
     * @param null $column
     * @return array|bool
     */
    public function last_insert_id($table, $column = null) {
        // Configurations
        $config = $this->_config;

        switch ($config['driver']) {
            case 'pgsql':
                // The sequence object created by PostgreSQL is automatically named [table]_[column]_seq
                $return = $this->_connection->lastInsertId("{$table}_{$column}_seq");
                break;
            case 'mysql':
                // In MySQL we can use special function
                $return = $this->select("SELECT LAST_INSERT_ID() FROM {$table}");
                break;
            default:
                $return = false;
                break;
        }

        return $return;
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
