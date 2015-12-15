<?php
/**
 * View - class for work with views
 */

namespace System\Core;

/**
 * Load views files class
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
