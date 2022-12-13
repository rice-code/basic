<?php

namespace Rice\Basic\Enum;

abstract class BaseEnum
{
    // 属性未定义
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    // 属性未定义
    public const METHOD_NOT_DEFINE = 'method_not_define';

    public const STRING_IS_EMPTY = 'string_is_empty';

    public const INVALID_PARAM = 'invalid_param';

    public const CLASS_DOES_NOT_EXIST = 'class_does_not_exist';

    public const CLASS_PROPERTY_IS_NOT_OVERRIDDEN = 'class_property_is_not_overridden';

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
