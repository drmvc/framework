<?php namespace System\Controllers;

use System\Core\Controller;
use System\Core\Config;
use System\Core\View;
use System\Core\Route;

/**
 * Class Sitemap
 * @package Application\Controllers
 */
class Sitemap extends Controller
{
    /**
     * Main constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function action_index()
    {
        $appnamespace = '\\Application\\Controllers\\';
        $sysnamespace = '\\System\\Controllers\\';

        $appfiles = glob(DOCROOT . 'Application/Controllers/*.php');
        //$sysfiles = glob(DOCROOT . 'System/Controllers/*.php');
        $sysfiles = array();
        $files = array_merge($appfiles, $sysfiles);

        $classes = array();
        $i = 0;
        foreach ($files as $file) {
            $segments = explode('/', $file);
            $segments = explode('\\', $segments[count($segments) - 1]);
            $filename = $segments[count($segments) - 1];
            $classname = explode('.', $filename);

            $appclass = $appnamespace . $classname[0];
            $sysclass = $sysnamespace . $classname[0];

            switch (true) {
                case (file_exists(DOCROOT . 'Application/Controllers/' . $classname[0] . '.php')):
                    $class_name = $appnamespace . $classname[0];
                    $class_check = new $appclass();
                    break;
                case (file_exists(DOCROOT . 'System/Controllers/' . $classname[0] . '.php')):
                    $class_name = $sysnamespace . $classname[0];
                    $class_check = new $sysclass();
                    break;
                default:
                    $class_name = NULL;
                    $class_check = NULL;
                    break;
            }

            $classes[$i]['name'] = $class_name;
            $classes[$i]['name_short'] = $classname[0];
            $classes[$i]['path'] = $file;
            $classes[$i]['methods'] = get_class_methods($class_check);

            $i++;
        }

        $data['sitemap'] = $classes;
        View::render('sitemap', $data);
    }
}
