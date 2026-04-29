<?php

namespace Rice\Basic\Support\Utils;

/**
 * 对象池模式实现
 * 用于管理频繁创建的对象，减少内存消耗和GC压力.
 */
class ObjectPool
{
    /**
     * @var array 存储对象池的数组
     */
    private static $pools = [];

    /**
     * @var array 对象池配置
     */
    private static $config = [
        'max_size' => 100, // 默认最大对象池大小
        'ttl'      => 3600, // 对象默认存活时间（秒）
    ];

    /**
     * 获取对象池中的对象
     *
     * @param string $className 类名
     * @param array  $params    构造函数参数
     * @param array  $options   池选项
     * @return object
     */
    public static function get(string $className, array $params = [], array $options = []): object
    {
        $poolKey    = self::getPoolKey($className, $params);
        $poolConfig = array_merge(self::$config, $options);

        // 初始化对象池（如果不存在）
        if (!isset(self::$pools[$poolKey])) {
            self::$pools[$poolKey] = [
                'objects'      => [],
                'last_cleanup' => time(),
            ];
        }

        // 清理过期对象
        self::cleanupExpiredObjects($poolKey, $poolConfig['ttl']);

        // 从池中获取对象（如果有可用的）
        if (!empty(self::$pools[$poolKey]['objects'])) {
            $object = array_shift(self::$pools[$poolKey]['objects']);

            return $object;
        }

        // 创建新对象
        return self::createObject($className, $params);
    }

    /**
     * 归还对象到池中.
     *
     * @param object $object 要归还的对象
     * @param array  $params 创建该对象时使用的参数
     */
    public static function return(object $object, array $params = []): void
    {
        $className  = get_class($object);
        $poolKey    = self::getPoolKey($className, $params);
        $poolConfig = self::$config;

        // 初始化对象池（如果不存在）
        if (!isset(self::$pools[$poolKey])) {
            self::$pools[$poolKey] = [
                'objects'      => [],
                'last_cleanup' => time(),
            ];
        }

        // 如果对象池未达到最大大小，则归还对象
        if (count(self::$pools[$poolKey]['objects']) < $poolConfig['max_size']) {
            // 如果对象有reset方法，则调用它重置状态
            if (method_exists($object, 'reset')) {
                $object->reset();
            }

            self::$pools[$poolKey]['objects'][] = [
                'object'      => $object,
                'return_time' => time(),
            ];
        }
    }

    /**
     * 清空指定类的对象池.
     *
     * @param string $className 类名
     * @param array  $params    构造函数参数
     */
    public static function clearPool(string $className, array $params = []): void
    {
        $poolKey = self::getPoolKey($className, $params);
        if (isset(self::$pools[$poolKey])) {
            self::$pools[$poolKey]['objects'] = [];
        }
    }

    /**
     * 获取对象池大小.
     *
     * @param string $className 类名
     * @param array  $params    构造函数参数
     * @return int
     */
    public static function getPoolSize(string $className, array $params = []): int
    {
        $poolKey = self::getPoolKey($className, $params);
        if (!isset(self::$pools[$poolKey])) {
            return 0;
        }

        return count(self::$pools[$poolKey]['objects']);
    }

    /**
     * 清理过期对象
     *
     * @param string $poolKey 池键
     * @param int    $ttl     对象存活时间（秒）
     */
    private static function cleanupExpiredObjects(string $poolKey, int $ttl): void
    {
        $now  = time();
        $pool = &self::$pools[$poolKey];

        // 如果距离上次清理不足10秒，则跳过
        if ($now - $pool['last_cleanup'] < 10) {
            return;
        }

        $pool['last_cleanup'] = $now;

        // 过滤出未过期的对象
        $pool['objects'] = array_filter($pool['objects'], function (array $item) use ($now, $ttl) {
            return $now - $item['return_time'] < $ttl;
        });
    }

    /**
     * 创建新对象
     *
     * @param class-string $className 类名
     * @param array  $params    构造函数参数
     * @return object
     */
    private static function createObject(string $className, array $params): object
    {
        // 处理空参数情况
        if (empty($params)) {
            return new $className();
        }

        // 使用反射创建带参数的对象
        $reflector = new \ReflectionClass($className);

        return $reflector->newInstanceArgs($params);
    }

    /**
     * 获取对象池的唯一键.
     *
     * @param string $className 类名
     * @param array  $params    构造函数参数
     * @return string
     */
    private static function getPoolKey(string $className, array $params): string
    {
        // 对参数数组进行排序并序列化，确保相同参数生成相同的键
        ksort($params);

        return md5($className . serialize($params));
    }

    /**
     * 设置全局对象池配置.
     *
     * @param array $config 配置数组
     */
    public static function setConfig(array $config): void
    {
        self::$config = array_merge(self::$config, $config);
    }
}
