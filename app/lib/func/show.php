<?php


namespace App\lib\func;


class show
{
    public static function success($message = 'OK',$code = 200,$data = [])
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error($message = 'error',$code = -100,$data = [])
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }
}
