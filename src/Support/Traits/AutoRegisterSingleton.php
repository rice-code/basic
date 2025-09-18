<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\FrameTypeUtil;

trait AutoRegisterSingleton
{
    use MagicMethodManager;

    public function registerSingleton(): void
    {
        if (FrameTypeUtil::isLaravel()) {
            app()->singleton(static::class, function () {
                return $this;
            });
        }
    }
}
