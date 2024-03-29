<?php

namespace Rice\Basic\Components\Enum;

abstract class BaseEnum
{
    /**
     * @en attr not define
     * @zh-CN 属性未定义
     */
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    /**
     * @en method not define
     * @zh-CN 属性未定义
     */
    public const METHOD_NOT_DEFINE = 'method_not_define';
    /**
     * @en string is empty
     * @zh-CN 字符串为空
     */
    public const STRING_IS_EMPTY = 'string_is_empty';
    /**
     * @en invalid param
     * @zh-CN 非法参数
     */
    public const INVALID_PARAM = 'invalid_param';
    /**
     * @en class does not exist
     * @zh-CN 类不存在
     */
    public const CLASS_DOES_NOT_EXIST = 'class_does_not_exist';

    /**
     * @en class property is not overridden
     * @zh-CN 类属性未被重写
     */
    public const CLASS_PROPERTY_IS_NOT_OVERRIDDEN = 'class_property_is_not_overridden';
    /**
     * @en file not exists
     * @zh-CN 文件不存在
     */
    public const FILE_NOT_EXISTS = 'file_not_exists';
    /**
     * @en dir not exists
     * @zh-CN 目录不存在
     */
    public const DIR_NOT_EXISTS = 'dir_not_exists';

    // 全部常量
    public static ?array $consts = null;

    // 父级常量
    public static ?array $parentConsts = null;

    // 子级常量
    public static $childConsts = null;

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
