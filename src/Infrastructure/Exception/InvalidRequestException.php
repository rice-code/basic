<?php

namespace Rice\Basic\Infrastructure\Exception;

use Rice\Basic\Infrastructure\Enum\BaseEnum;
use Rice\Basic\Infrastructure\Enum\HttpStatusCodeEnum;
use Rice\Basic\Infrastructure\Enum\InvalidRequestEnum;

class InvalidRequestException extends BaseException
{
    public static function httpStatusCode(): int
    {
        return HttpStatusCodeEnum::INVALID_REQUEST;
    }

    public static function enumClass(): string
    {
        return InvalidRequestEnum::class;
    }

    public static function default(): void
    {
        throw new self(InvalidRequestEnum::DEFAULT);
    }

    public static function InvalidParam(): void
    {
        throw new self(BaseEnum::INVALID_PARAM);
    }
}