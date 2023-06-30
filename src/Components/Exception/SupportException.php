<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\SupportEnum;

class SupportException extends BaseException
{
    public static function enumClass(): string
    {
        return SupportEnum::class;
    }
}
