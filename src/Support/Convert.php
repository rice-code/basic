<?php


namespace Rice\Basic\Support;


class Convert
{
    public static function objToArr($obj)
    {
        return json_decode(json_encode($obj, JSON_UNESCAPED_UNICODE), true);
    }

}