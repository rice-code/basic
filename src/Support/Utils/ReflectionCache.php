<?php

namespace Rice\Basic\Support\Utils;

/**
 * 反射对象缓存类
 * 用于缓存反射方法对象，避免频繁创建导致的性能问题.
 */
class ReflectionCache
{
    /**
     * 反射方法缓存.
     * @var array
     */
    private static array $methodCache = [];

    /**
     * 获取反射方法对象
     * @param string $className  类名
     * @param string $methodName 方法名
     * @return \ReflectionMethod 反射方法对象
     */
    public static function getMethod(string $className, string $methodName): \ReflectionMethod
    {
        $cacheKey = $className . '::' . $methodName;

        if (!isset(self::$methodCache[$cacheKey])) {
            self::$methodCache[$cacheKey] = new \ReflectionMethod($className, $methodName);
            self::$methodCache[$cacheKey]->setAccessible(true);
        }

        return self::$methodCache[$cacheKey];
    }

    /**
     * 清理特定类的反射缓存.
     * @param string $className 类名（可选，不提供则清理所有缓存）
     * @return void
     */
    public static function clearCache(string $className = null): void
    {
        if (null === $className) {
            self::$methodCache = [];

            return;
        }

        foreach (array_keys(self::$methodCache) as $cacheKey) {
            if (0 === strpos($cacheKey, $className . '::')) {
                unset(self::$methodCache[$cacheKey]);
            }
        }
    }
}
