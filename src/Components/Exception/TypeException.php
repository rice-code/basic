<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\components\Enum\LangEnum;

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
