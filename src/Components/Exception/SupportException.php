<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\LangEnum;

class SupportException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::SUPPORT;
    }
}
