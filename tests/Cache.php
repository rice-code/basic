<?php

namespace Tests;

use Rice\Basic\Infrastructure\PathManager;
use Rice\Basic\Contracts\CacheContract;

class Cache implements CacheContract
{
    /**
     * 设置缓存
     *
     * @param string $key   缓存�?     * @param mixed  $value 缓存�?     * @return bool
     */
    public function set(string $key, $value): bool
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        return file_put_contents($storage, json_encode([$key => $value])) !== false;
    }

    /**
     * 获取缓存
     *
     * @param string $key     缓存�?     * @param mixed  $default 默认�?     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        if (file_exists($storage)) {
            return json_decode(file_get_contents($storage), true, 512, JSON_THROW_ON_ERROR)[$key] ?? $default;
        }

        return $default;
    }

    /**
     * 检查缓存是否存�?     *
     * @param string $key 缓存�?     * @return bool
     */
    public function has(string $key): bool
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        return file_exists($storage);
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存�?     * @return bool
     */
    public function delete(string $key): bool
    {
        $storage = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR . $key;
        return file_exists($storage) && unlink($storage);
    }

    /**
     * 自增缓存�?     *
     * @param string $key  缓存�?     * @param int    $step 步长
     * @return int|bool
     */
    public function increment(string $key, int $step = 1)
    {
        $value = (int)$this->get($key, 0);
        $newValue = $value + $step;
        return $this->set($key, $newValue) ? $newValue : false;
    }

    /**
     * 自减缓存�?     *
     * @param string $key  缓存�?     * @param int    $step 步长
     * @return int|bool
     */
    public function decrement(string $key, int $step = 1)
    {
        $value = (int)$this->get($key, 0);
        $newValue = $value - $step;
        return $this->set($key, $newValue) ? $newValue : false;
    }

    /**
     * 清空所有缓�?     *
     * @return bool
     */
    public function clear(): bool
    {
        $directory = PathManager::getInstance()->test . 'Storage' . DIRECTORY_SEPARATOR;
        $files = glob($directory . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * 获取缓存并存储默认值（如果不存在）
     *
     * @param string   $key      缓存�?     * @param callable $callback 回调函数，返回默认�?     * @param int      $ttl      过期时间
     * @return mixed
     */
    public function remember(string $key, callable $callback, int $ttl = 0)
    {
        $value = $this->get($key);
        if ($value === null) {
            $value = $callback();
            $this->set($key, $value);
        }
        return $value;
    }

    /**
     * 永久存储缓存�?     *
     * @param string   $key      缓存�?     * @param callable $callback 回调函数，返回缓存�?     * @return mixed
     */
    public function rememberForever(string $key, callable $callback)
    {
        return $this->remember($key, $callback);
    }

    /**
     * 删除缓存（与 delete 方法相同�?     *
     * @param string $key 缓存�?     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->delete($key);
    }
}
