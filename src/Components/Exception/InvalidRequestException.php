<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\BaseEnum;
use Rice\Basic\Components\Enum\HttpStatusCodeEnum;
use Rice\Basic\Components\Enum\InvalidRequestEnum;

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

    /**
     * @throws InvalidRequestException
     */
    public static function default(): void
    {
        throw new self(InvalidRequestEnum::DEFAULT);
    }

    /**
     * @throws InvalidRequestException
     */
    public static function InvalidParam(): void
    {
        throw new self(BaseEnum::INVALID_PARAM);
    }
}
