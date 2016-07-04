<?php namespace Modules\Database\Core;

/**
 * Database Model base class, this class like abstracted class, should be reassign via another driver class
 * @package Modules\Database\Core
 */

use System\Core\Config;
use System\Core\Model as System_Model;

abstract class Model extends System_Model
{
    // Database protected instance
    protected $_db;

    // Database public instance
    public $db;

    /**
     * Create a new model instance
     *
     * @param $name
     * @param null $db
     * @return bool
     */
    public static function init($name, $db = null)
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

    /**
     * Open connect to database after model start construct
     *
     * @param null $db
     */
    public function __construct($db = null)
    {
        if ($db) {
            // Set the instance or name
            $this->_db = $db;
        } elseif (!$this->_db) {
            // Use the default name
            $this->_db = Database::$default;
        }

        $config = Config::load('database', true);
        $this->_config = $config[$this->_db];
        $this->_config['path'] = $config['path'];

        if (is_string($this->_db)) {
            // Load the database
            $this->db = Database::init($this->_db);
        }
    }

}
