<?php

namespace Rice\Basic\Infrastructure\Exception;

use Rice\Basic\Infrastructure\Enum\SupportEnum;
use Rice\Basic\Infrastructure\Enum\HttpStatusCodeEnum;

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