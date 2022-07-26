<?php


namespace Rice\Basic\Exception;


class CommonException extends BaseException
{
    public const STRING_IS_EMPTY = 'string_is_empty';

    public const INVALID_PARAM = 'invalid_param';

    public const CLASS_DOES_NOT_EXIST = 'class_does_not_exist';

    public const CLASS_PROPERTY_IS_NOT_OVERRIDDEN = 'class_property_is_not_overridden';

    public static function getLangName(): string
    {
        return 'common';
    }
}