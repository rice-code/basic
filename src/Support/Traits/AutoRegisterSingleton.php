<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\FrameTypeUtil;

trait AutoRegisterSingleton
{
    public function registerSingleton(): void
    {
        if (FrameTypeUtil::isLaravel()) {
            app()->singleton(static::class, function () {
                return $this;
            });
        }
    }

    public static function __callStatic($method, $params = [])
    {
        if (FrameTypeUtil::isLaravel()) {
            return app(static::class)->$method(...$params);
        }
    }
}
