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
     *
     * @param $value
     * @param null $type
     * @return mixed|string
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
                $value = preg_replace("/[^а-яёa-z0-9\—\~\`\.\,\@\%\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\r]/iu", "", $value);
                break;
        }

        return $value;
    }

    /**
     * Generate selectors
     *
     * @param $name
     * @param $arr
     * @param int $test
     * @param null $data_id
     * @return string
     */
    public static function selector($name, $arr, $test = -1, $data_id = NULL)
    {
        $out = "<select class='" . $name . " form-control' name='" . $name . "' data-id='" . $data_id . "'>";
        //$out = $out."<option value='NULL' disabled selected>---</option>";
        $out = $out . "<option value='NULL' selected>---</option>";
        $i = 0;
        while ($i < count($arr)) {
            if ($test != $arr[$i]->id) {
                $out = $out . "<option value='" . $arr[$i]->id . "'>" . $arr[$i]->name . "</option>";
            } else {
                $out = $out . "<option value='" . $arr[$i]->id . "' selected>" . $arr[$i]->name . "</option>";
            }
            $i++;
        }
        $out = $out . "</select>";
        return $out;
    }

    /**
     * Generate checkbox
     *
     * @param $name
     * @param $status
     * @param null $id
     * @return string
     */
    public static function checkbox($name, $status, $id = NULL)
    {
        if ('t' == $status) $ch = 'checked'; else  $ch = '';
        return "<input type='checkbox' class='checkbox " . $name . "' data-id='" . $id . "' name='" . $name . "' " . $ch . ">";
    }
}
