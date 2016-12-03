<?php namespace Modules\QveriBilder\Core;

/**
 * Class for generate the SQL requests
 * @package Modules\Database\Core
 */
class QveriBilder
{
    private $_query;

    public function get()
    {
        return $this->_query . ";\n";
    }

    public function create()
    {
        $this->_query = 'CREATE ';
        return $this;
    }

    public function database($name)
    {
        $this->_query .= ' DATABASE ' . $name;
        return $this;
    }

    public function table($name)
    {
        $this->_query .= ' TABLE ' . $name;
        return $this;
    }

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

    public function from($name)
    {
        $this->_query .= ' FROM ' . $name;
        return $this;
    }

    public function left_join($name, $first, $second, $mode = '=')
    {
        $this->_query .= ' LEFT JOIN ' . $name . ' ON (' . $first . ' ' . $mode . ' ' . $second . ')';
        return $this;
    }

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
