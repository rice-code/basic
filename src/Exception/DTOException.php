<?php

namespace Rice\Basic\Exception;

/**
 * 数据传输对象异常
 * Class DTOException
 * @package Rice\Basic\Exception
 */
class DTOException extends BaseException
{
    // 属性未定义
    public const ATTR_NOT_DEFINE = 'attr_not_define';
    // 属性未定义
    public const METHOD_NOT_DEFINE = 'method_not_define';

    public static function getLangName(): string
    {
        return 'dto';
    }
}