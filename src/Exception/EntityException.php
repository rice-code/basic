<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

/**
 * Class EntityException.
 */
class EntityException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::ENTITY;
    }
}
