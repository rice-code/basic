<?php


namespace Rice\Basic\Exception;


class CommonException extends BaseException
{
    public const STRING_IS_EMPTY = 'string_is_empty';

    public const INVALID_PARAM = 'invalid_param';

    public static function getLangName(): string
    {
        return 'common';
    }
}