<?php

namespace App\Http\Libraries;

class AppLib
{
    public static function response($code=200, $data=array(), $message='')
    {
        $array = array(
            'code' => $code,
            'data' => $data,
            'message' => $message
        );
        return \json_encode($array);
    }

    public static function dateHuman($tanggal)
    {
        $array = explode('-', $tanggal);
        return $array[2].'-'.$array[1].'-'.$array[0];
    }
}