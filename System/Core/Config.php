<?php namespace System\Core;

/**
 * Class Config for access to application and system configs
 * @package System\Core
 */
class Config
{

    /**
     * Load configuration file, show config path if needed
     *
     * @param  string  $name
     * @param  boolean $show_path
     * @return mixed|null
     */
    public static function load($name, $show_path = false)
    {
        // Application config
        $appconfig = APPPATH . 'Configs' . DIRECTORY_SEPARATOR . $name . '.php';
        // System config
        $sysconfig = SYSPATH . 'Configs' . DIRECTORY_SEPARATOR . $name . '.php';

        switch (true) {
            case file_exists($appconfig):
                $config = include($appconfig);
                if ($show_path) $config['path'] = $appconfig;
                break;
            case file_exists($sysconfig):
                $config = include($sysconfig);
                if ($show_path) $config['path'] = $sysconfig;
                break;
            default:
                $config = NULL;
                echo "$appconfig not found\n";
                echo "$sysconfig not found\n";
                break;
        }

        return $config;
    }


}
