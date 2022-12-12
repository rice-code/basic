<?php

namespace Rice\Basic\Exception;

/**
 * Class EntityException.
 */
class EntityException extends BaseException
{
    public static function getLangName(): string
    {
        return 'entity';
    }
}
