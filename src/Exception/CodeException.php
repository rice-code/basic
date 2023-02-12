<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

class CodeException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::CODE;
    }
}
