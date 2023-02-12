<?php

namespace Rice\Basic\Entity;

class FrameEntity extends BaseEntity
{
    private static array $_filter = [
        '_setter',
        '_getter',
        '_readOnly',
        '_params',
        '_properties',
        '_alias',
        '_cache',
        '_idx',
    ];

    public static function getFilter(): array
    {
        return self::$_filter;
    }

    public static function inFilter($needle): bool
    {
        return in_array($needle, self::getFilter(), true);
    }
}