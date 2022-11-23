<?php

namespace Rice\Basic\Exception;

/**
 * setting/getting 类型异常
 * Class AccessorException.
 */
class AccessorException extends BaseException
{
    // 属性未定义
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    // 属性未定义
    public const METHOD_NOT_DEFINE = 'method_not_define';

    public static function getLangName(): string
    {
        return 'accessor';
    }
}
