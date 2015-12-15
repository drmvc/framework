<?php

namespace System\Core;

class Config
{

    /**
     * Load configuration file
     *
     * @param  string $name
     * @return mixed|null
     */
    public static function load($name)
    {
        // Application config
        $appconfig = APPPATH . 'Configs' . DIRECTORY_SEPARATOR . $name . '.php';
        // System config
        $sysconfig = SYSPATH . 'Configs' . DIRECTORY_SEPARATOR . $name . '.php';

        switch (true) {
            case file_exists($appconfig):
                $config = include($appconfig);
                $config['path'] = $appconfig;
                break;
            case file_exists($sysconfig):
                $config = include($sysconfig);
                $config['path'] = $sysconfig;
                break;
            default:
                $config = NULL;
                break;
        }

        return $config;
    }


}
