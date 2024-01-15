<?php

namespace Tests;

use JsonException;
use Rice\Basic\PathManager;
use Rice\Basic\Contracts\CacheContract;

class Cache implements CacheContract
{
    public function set($key, $value)
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        file_put_contents($storage, $value);
    }

    /**
     * @throws JsonException
     */
    public function get($key, $default = null)
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        if (file_exists($storage)) {
            return json_decode(file_get_contents($storage), true, 512, JSON_THROW_ON_ERROR)[$key] ?? $default;
        }

        return $default;
    }
}
