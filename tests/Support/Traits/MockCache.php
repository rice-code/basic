<?php

namespace Tests\Support\Traits;

/**
 * 模拟的缓存实现类.
 */
class MockCache implements \Rice\Basic\Contracts\CacheContract
{
    /**
     * @return true
     */
    public function set($key, $value)
    {
        return true;
    }

    public function get($key, $default = null)
    {
        return $default;
    }
}
