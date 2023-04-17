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
}
