<?php

namespace Rice\Basic\Enum;

abstract class BaseEnum
{
    // 属性未定义
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    // 属性未定义
    public const METHOD_NOT_DEFINE = 'method_not_define';

    // 全部常量
    public static $consts = null;

    // 父级常量
    public static $parentConsts = null;

    // 子级常量
    public static $childConsts = null;

    public static function getConstants(): array
    {
        if (is_null(self::$consts)) {
            return self::$consts = (new \ReflectionClass(static::class))->getConstants();
        }

        return self::$consts;
    }

    public static function getParentConstants(): array
    {
        if (is_null(self::$parentConsts)) {
            return self::$parentConsts = (new \ReflectionClass(self::class))->getConstants();
        }

        return self::$parentConsts;
    }

    public static function getChildConstants(): array
    {
        if (is_null(self::$childConsts)) {
            return self::$childConsts = array_diff(self::getConstants(), self::getParentConstants());
        }

        return self::$childConsts;
    }
}
