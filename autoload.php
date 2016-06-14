<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    //$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fileName .= $className . '.php';

    // Now we should fix path for separated projects
    $patterns = array('/^System/', '/^Application/');
    $replacements = array(SYSPATH, APPPATH);
    $fileName = preg_replace($patterns, $replacements, $fileName);

    require $fileName;
}

spl_autoload_register('autoload');