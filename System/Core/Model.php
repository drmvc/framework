<?php namespace System\Core;

/**
 * Class Model for work with simple models
 * @package System\Core
 */
class Model
{
    /**
     * Init new model instance
     *
     * @param  string $name
     * @param  null   $db
     * @return mixed|null
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

}
