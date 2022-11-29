<?php

namespace Rice\Basic\Support\Converts;

class TypeConvert
{
    public static function objToArr($obj)
    {
        return json_decode(json_encode($obj, JSON_UNESCAPED_UNICODE), true);
    }
}
