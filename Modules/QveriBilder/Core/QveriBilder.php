<?php namespace Modules\QveriBilder\Core;

/**
 * Class for generate the SQL requests
 * @package Modules\Database\Core
 */
class QveriBilder
{
    private $_query;

    /**
     * Return query to stdout
     *
     * @return string
     */
    public function get()
    {
        return $this->_query . ";\n";
    }

    /**
     * Create string
     *
     * @return $this
     */
    public function create()
    {
        $this->_query = 'CREATE ';
        return $this;
    }

    /**
     * Database string
     *
     * @param $name
     * @return $this
     */
    public function database($name)
    {
        $this->_query .= ' DATABASE ' . $name;
        return $this;
    }

    /**
     * Table string
     *
     * @param $name
     * @return $this
     */
    public function table($name)
    {
        $this->_query .= ' TABLE ' . $name;
        return $this;
    }

    /**
     * Generate columns lists from array
     *
     * @param $array
     * @return $this
     */
    public function columns($array)
    {
        $columnDetails = null;
        $primary = null;
        foreach ($array as $key => $value) {
            $columnDetails .= "\n" . $value[0] . ' ' . $value[1];
            if (!empty($value[2])) $columnDetails .= ' ' . $value[2];
            if (!empty($value[3])) $columnDetails .= ' ' . $value[3];
            if (!empty($value[4]) && $value[4] === true) $primary = 'PRIMARY (' . $value[0] . ')';
            $columnDetails .= ",";
        }

        if (!empty($value[4]) && $value[4] !== true) {
            $columnDetails = rtrim($columnDetails, ',');
        }

        $this->_query .= '(' . $columnDetails . "\n" . $primary . "\n)";
        return $this;
    }

    /**
     * Select string with WHAT definitions
     *
     * @param null $what
     * @return $this
     */
    public function select($what = null)
    {
        if ($what == null) {
            $whatDetails = '*';
        } else {
            $whatDetails = null;
            foreach ($what as $key) {
                $whatDetails .= ", $key";
            }
            $whatDetails = ltrim($whatDetails, ',');
        }

        $this->_query = 'SELECT ' . $whatDetails;
        return $this;
    }

    /**
     * From string
     *
     * @param $name
     * @return $this
     */
    public function from($name)
    {
        $this->_query .= ' FROM ' . $name;
        return $this;
    }

    /**
     * Left loin string with parameters
     *
     * @param $name
     * @param $first
     * @param $second
     * @param string $mode
     * @return $this
     */
    public function left_join($name, $first, $second, $mode = '=')
    {
        $this->_query .= ' LEFT JOIN ' . $name . ' ON (' . $first . ' ' . $mode . ' ' . $second . ')';
        return $this;
    }

    /**
     * Where string
     *
     * @param $where
     * @return $this
     */
    public function where($where)
    {
        $whereDetails = null;
        foreach ($where as $key => $value) {
            if (substr($value, 0, 1) === ':') {
                $whereDetails .= " AND $key = $value";
            } else {
                $whereDetails .= " AND $key = '$value'";
            }
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $this->_query .= ' WHERE ' . $whereDetails;

        return $this;
    }

    /**
     * Update string
     *
     * @param $table
     * @param $data
     * @return $this
     */
    public function update($table, $data)
    {
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            if (substr($value, 0, 1) === ':') {
                $fieldDetails .= "$key = '$value',";
            } else {
                $fieldDetails .= "$key = '$value',";
            }
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $this->_query = "UPDATE $table SET $fieldDetails ";
        return $this;
    }

}
