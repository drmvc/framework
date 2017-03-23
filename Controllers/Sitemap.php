<?php namespace DrMVC\Controllers;

use DrMVC\Core\Controller;
use DrMVC\Core\Config;
use DrMVC\Core\View;
use DrMVC\Core\Route;

/**
 * Class Sitemap for sitemap.xml generation
 * @package System\Controllers
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

    /**
     * sitemap.xml alias page
     */
    public function action_index()
    {
        $appnamespace = '\\Application\\Controllers\\';
        $sysnamespace = '\\System\\Controllers\\';

        $appfiles = glob(APPPATH . '/Controllers/*.php');
        //$sysfiles = glob(DOCROOT . 'System/Controllers/*.php');
        $sysfiles = array();
        $files = array_merge($appfiles, $sysfiles);

        $classes = array();
        $i = 0;
        while ($i < count($files)) {
            $file = $files[$i];
            $segments = explode('/', $file);
            $segments = explode('\\', $segments[count($segments) - 1]);
            $filename = $segments[count($segments) - 1];
            $classname = explode('.', $filename);

            $appclass = $appnamespace . $classname[0];
            $sysclass = $sysnamespace . $classname[0];

            switch (true) {
                case (file_exists(APPPATH . '/Controllers/' . $classname[0] . '.php')):
                    $class_name = $appnamespace . $classname[0];
                    $class_check = $appclass;
                    break;
                case (file_exists(SYSPATH . '/Controllers/' . $classname[0] . '.php')):
                    $class_name = $sysnamespace . $classname[0];
                    $class_check = $sysclass;
                    break;
                default:
                    $class_name = null;
                    $class_check = false;
                    break;
            }

            $classes[$i]['name'] = $class_name;
            $classes[$i]['name_short'] = $classname[0];
            $classes[$i]['path'] = $file;

            $methods = get_class_methods($class_check);
            $m = 0;
            while ($m < count($methods)) {
                if (preg_match('#^action_#i', $methods[$m]) === 1) $classes[$i]['methods'][] = $methods[$m];
                $m++;
            }

            $i++;
        }

        $data['sitemap'] = $classes;
        View::render('sitemap', $data);
    }
}
