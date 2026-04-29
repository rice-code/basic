<?php

namespace Tests\Support\Traits;

/**
 * 模拟的缓存实现类.
 */
class MockCache implements \Rice\Basic\Contracts\CacheContract
{
    /**
     * 设置缓存
     *
     * @param string $key   缓存键
     * @param mixed  $value 缓存值
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        return true;
    }

    /**
     * 获取缓存
     *
     * @param string $key     缓存键
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $default;
    }

    /**
     * 检查缓存是否存在
     *
     * @param string $key 缓存键
     * @return bool
     */
    public function has(string $key): bool
    {
        return false;
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存键
     * @return bool
     */
    public function delete(string $key): bool
    {
        return true;
    }

    /**
     * 自增缓存值
     *
     * @param string $key  缓存键
     * @param int    $step 步长
     * @return int|bool
     */
    public function increment(string $key, int $step = 1)
    {
        return $step;
    }

    /**
     * 自减缓存值
     *
     * @param string $key  缓存键
     * @param int    $step 步长
     * @return int|bool
     */
    public function decrement(string $key, int $step = 1)
    {
        return -$step;
    }

    /**
     * 清空所有缓存
     *
     * @return bool
     */
    public function clear(): bool
    {
        return true;
    }

    /**
     * 获取缓存并存储默认值（如果不存在）
     *
     * @param string   $key      缓存键
     * @param callable $callback 回调函数，返回默认值
     * @param int      $ttl      过期时间
     * @return mixed
     */
    public function remember(string $key, callable $callback, int $ttl = 0)
    {
        return $callback();
    }

    /**
     * 永久存储缓存值
     *
     * @param string   $key      缓存键
     * @param callable $callback 回调函数，返回缓存值
     * @return mixed
     */
    public function rememberForever(string $key, callable $callback)
    {
        return $callback();
    }

    /**
     * 删除缓存（与 delete 方法相同）
     *
     * @param string $key 缓存键
     * @return bool
     */
    public function forget(string $key): bool
    {
        return true;
    }
}
