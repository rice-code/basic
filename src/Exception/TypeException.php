<?php


namespace Rice\Basic\Exception;

/**
 * 数据类型异常
 * Class TypeException
 * @package Rice\Basic\Exception
 */
class TypeException extends BaseException
{
    public const INVALID_TYPE = 'invalid type';

    public static function getLangName(): string
    {
        return 'type';
    }
}