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
}