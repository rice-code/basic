<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\LangEnum;

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
