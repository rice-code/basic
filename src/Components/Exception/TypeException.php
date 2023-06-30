<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\TypeEnum;

/**
 * 数据类型异常
 * Class TypeException.
 */
class TypeException extends BaseException
{
    public static function enumClass(): string
    {
        return TypeEnum::class;
    }
}
