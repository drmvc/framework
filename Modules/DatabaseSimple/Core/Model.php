<?php namespace Modules\DatabaseSimple\Core;

/**
 * DatabaseSimple Model base class
 * @package Modules\DatabaseSimple\Core
 */

use System\Core\Config;
use System\Core\Model as System_Model;

abstract class Model extends System_Model
{
    /**
     * Create a new model instance.
     *
     * @param $name
     * @param null $db
     * @return bool
     */
    public static function init($name, $db = NULL)
    {
        $prefix = '\\Application\\Models\\';
        $model = $prefix . ucfirst(strtolower($name));

        // Filename for check
        $model_file = DOCROOT . str_replace('\\', DIRECTORY_SEPARATOR, $model . '.php');

        // If model exist
        if (file_exists($model_file)) {
            return new $model($db);
        } else {
            return false;
        }
    }

    // Database protected instance
    protected $_db;

    /**
     * Load database if dbname exist
     *
     * @param null $db
     */
    public function __construct($db = NULL)
    {
        if ($db) {
            // Set the instance or name
            $this->_db = $db;
        } elseif (!$this->_db) {
            // Use the default name
            $this->_db = Database::$default;
        }

        $this->_config = Config::load('database_simple');

        if (is_string($this->_db)) {
            // Load the database
            $this->db = Database::init($this->_db);
        }
    }

}
