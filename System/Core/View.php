<?php namespace System\Core;

/**
 * Class View for work with views
 */
class View
{
    /**
     * Include layout file
     *
     * @param string $path
     * @param bool   $data
     * @param bool   $error
     */
    public static function render($path, $data = false, $error = false)
    {
        require APPPATH . "Views/$path.php";
    }

}
