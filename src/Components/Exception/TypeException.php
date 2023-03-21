<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\LangEnum;

/**
 * 数据类型异常
 * Class TypeException.
 */
class TypeException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::TYPE;
    }
}
