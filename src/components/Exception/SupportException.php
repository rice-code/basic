<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\components\Enum\LangEnum;

class SupportException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::SUPPORT;
    }
}
