<?php

namespace Rice\Basic\Infrastructure\Enum;

/**
 * @en attr_not_define Attribute not defined
 * @zh-CN attr_not_define 属性未定义
 * @en method_not_define method not define
 * @zh-CN method_not_define 方法未定义
 * @en string_is_empty String is empty
 * @zh-CN string_is_empty 字符串为空
 * @en invalid_param Invalid parameter
 * @zh-CN invalid_param 参数无效
 * @en class_does_not_exist Class does not exist
 * @zh-CN class_does_not_exist 类不存在
 * @en class_property_is_not_overridden Class property is not overridden
 * @zh-CN class_property_is_not_overridden 类属性未被重写
 * @en file_not_exists File not exists
 * @zh-CN file_not_exists 文件不存在
 * @en dir_not_exists Directory not exists
 * @zh-CN dir_not_exists 目录不存在
 */
abstract class BaseEnum
{
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    public const METHOD_NOT_DEFINE = 'method_not_define';
    public const STRING_IS_EMPTY = 'string_is_empty';
    public const INVALID_PARAM = 'invalid_param';
    public const CLASS_DOES_NOT_EXIST = 'class_does_not_exist';
    public const CLASS_PROPERTY_IS_NOT_OVERRIDDEN = 'class_property_is_not_overridden';
    public const FILE_NOT_EXISTS = 'file_not_exists';
    public const DIR_NOT_EXISTS = 'dir_not_exists';

    public static ?array $consts = null;
    public static ?array $parentConsts = null;
    public static $childConsts;

    public static function getConstants(): array
    {
        return self::$consts[static::class] ?? (self::$consts[static::class] = (new \ReflectionClass(static::class))->getConstants());
    }

    public static function getParentConstants(): array
    {
        return self::$parentConsts[static::class] ?? (self::$parentConsts[static::class] = (new \ReflectionClass(self::class))->getConstants());
    }

    public static function getChildConstants(): array
    {
        return self::$childConsts[static::class] ?? (self::$childConsts[static::class] = array_diff_key(self::getConstants(), self::getParentConstants()));
    }
}
