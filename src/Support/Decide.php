<?php

namespace Rice\Basic\Support;

class Decide
{
    public static function notNull($obj): bool
    {
        return !is_null($obj);
    }

    public static function notEmpty($obj): bool
    {
        return !empty($obj);
    }

    public static function notNullAndNotEmpty($obj): bool
    {
        return self::notNull($obj) && self::notEmpty($obj);
    }
}
