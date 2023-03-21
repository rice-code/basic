<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\LangEnum;

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
