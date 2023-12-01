<?php

namespace Rice\Basic\Support\Utils;

class ArrUtil
{
    public static function wrap($str): array
    {
        if (is_string($str)) {
            $str = [$str];
        }

        return $str;
    }
}
