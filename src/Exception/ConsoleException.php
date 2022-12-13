<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

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
