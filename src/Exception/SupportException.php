<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

class SupportException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::SUPPORT;
    }
}
