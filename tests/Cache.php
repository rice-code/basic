<?php

namespace Tests;

use Rice\Basic\PathManager;
use Rice\Basic\Contracts\CacheContract;

class Cache implements CacheContract
{
    public function set($key, $value)
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        file_put_contents($storage, $value);
    }

    public function get($key)
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        file_get_contents($storage);
    }
}
