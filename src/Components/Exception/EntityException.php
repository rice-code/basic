<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\components\Enum\LangEnum;

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
