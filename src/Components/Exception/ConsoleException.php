<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\components\Enum\LangEnum;

/**
 * Class ConsoleException.
 */
class ConsoleException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::CONSOLE;
    }
}
