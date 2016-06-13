<?php namespace System\Core;

/**
 * Class View for work with views
 */
class View
{
    /**
     * Include layout file
     */
    public static function render($path, $data = false, $error = false)
    {
        // Application view
        $appfile = APPPATH . 'Views' . DIRECTORY_SEPARATOR . $path . '.php';
        // System view
        $sysfile = SYSPATH . 'Views' . DIRECTORY_SEPARATOR . $path . '.php';

        switch (true) {
            case file_exists($appfile):
                $file = include($appfile);
                break;
            case file_exists($sysfile):
                $file = include($sysfile);
                break;
            default:
                $file = NULL;
                break;
        }

        return $file;
    }

}
