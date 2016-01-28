<?php
/**
 * Helper class with simple functions
 */

namespace System\Core;


class Helpers
{

    /**
     * Multidimensional search
     *
     * @param $parents
     * @param $searched
     * @param $object
     * @return bool|int|string
     */
    function md_search($parents, $searched, $object = true)
    {
        if (empty($searched) || empty($parents)) {
            return false;
        }

        $for_out = null;
        foreach ($parents as $key => $value) {
            $exists = true;

            foreach ($searched as $skey => $svalue) {
                if ($object)
                    $exists = ($exists && isset($value->$skey) && ($value->$skey == $svalue));
                else
                    $exists = ($exists && isset($value[$skey]) && ($value[$skey] == $svalue));
            }

            if ($exists) {
                $for_out[] = $key;
            }
        }
        if (!empty($for_out)) {
            return $for_out;
        } else {
            return false;
        }
    }


    /**
     * Cleanup the value
     */
    public static function cleaner($value, $type = NULL)
    {

        switch ($type) {
            case 'num':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9]/i", "", $value);
                break;
            case 'numex':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9\,]/i", "", $value);
                break;
            case 'text':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z]/iu", "", $value);
                break;
            case 'api':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z0-9\-\_\.]/iu", "", $value);
                break;
            case 'filename':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z\.\,\_\-\+\=\?\(\)\!0-9]/iu", "", $value);
                break;
            default:
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z0-9\~\`\.\,\@\%\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\r]/iu", "", $value);
                break;
        }

        return $value;
    }

}
