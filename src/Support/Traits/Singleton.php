<?php

namespace Rice\Basic\Support\Traits;

trait Singleton
{
    /**
     * 单例实例.
     *
     * @var static|null
     */
    private static $instance;

    /**
     * 私有构造函数，防止外部实例化.
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * 私有克隆方法，防止克隆.
     *
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * 防止反序列化创建新实例.
     *
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        throw new \RuntimeException('Cannot unserialize singleton instance.');
    }

    /**
     * 获取单例实例.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 重置单例实例（仅用于测试环境）.
     *
     * @codeCoverageIgnore
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}
