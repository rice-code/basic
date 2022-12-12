<?php

namespace Rice\Basic\Enum;

abstract class BaseEnum
{
    public static function getConstants()
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
