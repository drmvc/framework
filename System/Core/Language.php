<?php namespace System\Core;

/**
 * Language class to load the requested language file
 * @package System\Core
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
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code" . DIRECTORY_SEPARATOR . "$name.php'");
            die;
        }
    }

    /**
     * Read lines from file
     *
     * @param $name
     * @param mixed|string $code
     * @return mixed
     */
    public function read($name, $code = LANGUAGE_CODE)
    {
        /** lang file */
        $file = APPPATH . "Language" . DIRECTORY_SEPARATOR . "$code" . DIRECTORY_SEPARATOR . "$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            return include($file);
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code/$name.php'");
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
