<?php namespace Modules\DatabaseSimple\Core;

/**
 * Extending PDO to use custom methods
 * @package Modules\DatabaseSimple\Core
 */

use System\Core\Config;
use PDO;

class Database extends PDO
{
    public static $default = 'default';
    public static $instances = array();

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

        if ($config === NULL) {
            // Load the configuration for this database
            $config = Config::load('database_simple');
        }

        // Group information
        $type = $config['DB_TYPE'];
        $host = $config['DB_HOST'];
        $n_me = $config['DB_NAME'];
        $user = $config['DB_USER'];
        $pass = $config['DB_PASS'];

        // Checking if the same
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        // I've run into problem where
        // SET NAMES "UTF8" not working on some hostings.
        // Specifiying charset in DSN fixes the charset problem perfectly!
        $instance = new Database("$type:host=$host;dbname=$n_me;", $user, $pass);
        $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Setting Database into $instances to avoid duplication
        self::$instances[$name] = $instance;

        return $instance;
    }

    /**
     * run raw sql queries
     *
     * @param  string $sql sql command
     * @return mixed query
     */
    public function raw($sql)
    {
        return $this->query($sql);
    }

    /**
     * method for selecting records from a database
     *
     * @param  string $sql sql query
     * @param  array $array named params
     * @param  $fetchMode
     * @param  string $class class name
     * @return array returns an array of records
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ, $class = '')
    {
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }

        $stmt->execute();

        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
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

        $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->lastInsertId();
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

        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Delete method
     *
     * @param  string $table table name
     * @param  array $where array of columns and values
     * @param  integer $limit limit number of records
     * @return string
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Truncate table
     *
     * @param  string $table table name
     * @return bool
     */
    public function truncate($table)
    {
        return $this->exec("TRUNCATE TABLE $table");
    }
}
