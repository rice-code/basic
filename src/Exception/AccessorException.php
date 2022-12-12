<?php

namespace Rice\Basic\Exception;

/**
 * setting/getting 类型异常
 * Class AccessorException.
 */
class AccessorException extends BaseException
{

    public static function getLangName(): string
    {
        return 'accessor';
    }
}
