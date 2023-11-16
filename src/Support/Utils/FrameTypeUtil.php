<?php

namespace Rice\Basic\Support\Utils;

class FrameTypeUtil
{
    public static function isLaravel(): bool
    {
        if (defined('LARAVEL_START')) {
            return true;
        }

        return false;
    }

    public static function isPHP(string $version): bool
    {
        [$x] = explode('.', PHP_VERSION);
        return $x === $version;
    }
}
