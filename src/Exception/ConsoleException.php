<?php

namespace Rice\Basic\Exception;

/**
 * Class ConsoleException.
 */
class ConsoleException extends BaseException
{
    public static function getLangName(): string
    {
        return 'console';
    }
}
