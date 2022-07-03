<?php


namespace Rice\Basic\Exception;


class CommonException extends BaseException
{
    public const STRING_IS_EMPTY = 'string_is_empty';

    public const INVALID_PARAM = 'invalid_param';

    public const CLASS_DOES_NOT_EXIST = 'class_does_not_exist';

    public static function getLangName(): string
    {
        return 'common';
    }
}