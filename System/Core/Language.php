<?php
/**
 * Language - simple language loader
 */

namespace System\Core;

/**
 * Language class to load the requested language file.
 */
class Language
{
    /**
     * Variable holds array with language.
     *
     * @var array
     */
    private $array;

    /**
     * Variable holds language filename.
     *
     * @var string
     */
    public $filename;

    /**
     * Construct this class
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Load language function.
     *
     * @param string $name
     * @param string $code
     */
    public function load($name, $code = LANGUAGE_CODE)
    {
        /** lang file */
        $file = APPPATH . "Language" . DIRECTORY_SEPARATOR . "$code" . DIRECTORY_SEPARATOR . "$name.php";

        /** keep name */
        $this->filename = $name;

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $this->array[$name] = include($file);
            /** return to caller */
            return $this->array[$name];
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code" . DIRECTORY_SEPARATOR . "$name.php'");
            die;
        }
    }

    /**
     * Get element from language array by key.
     *
     * @param  string $value
     * @return string
     */
    public function get($value)
    {
        if (!empty($this->array[$this->filename][$value])) {
            return $this->array[$this->filename][$value];
        } else {
            return $value;
        }
    }

}
