<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\HttpStatusCodeEnum;
use Rice\Basic\Components\Enum\SupportEnum;

class InternalServerErrorException extends BaseException
{
    public static function httpStatusCode(): int
    {
        return HttpStatusCodeEnum::INTERNAL_SERVER_ERROR;
    }

    public static function enumClass(): string
    {
        return SupportEnum::class;
    }
}
