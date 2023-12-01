<?php

namespace Rice\Basic\Components\Entity;

class FrameEntity extends BaseEntity
{
    private static array $_filter = [
        '_setter'      => '_setter',
        '_getter'      => '_getter',
        '_readOnly'    => '_readOnly',
        '_params'      => '_params',
        '_properties'  => '_properties',
        '_alias'       => '_alias',
        '_cache'       => '_cache',
        '_idx'         => '_idx',
    ];

    public static function getFilter(): array
    {
        return self::$_filter;
    }

    public static function inFilter($needle): bool
    {
        return isset(self::$_filter[$needle]);
    }
}
