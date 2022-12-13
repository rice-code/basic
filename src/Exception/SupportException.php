<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

class SupportException extends BaseException
{
    public const CANNOT_DIVIDE_BY_ZERO = 'cannot_divide_by_zero';

    public static function getLangName(): string
    {
        return LangEnum::SUPPORT;
    }
}
