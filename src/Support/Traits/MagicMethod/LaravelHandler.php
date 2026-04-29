<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

use Rice\Basic\Support\Utils\FrameTypeUtil;

class LaravelHandler extends AbstractHandler
{
    /**
     * 优先级：10（非常高优先级）
     *
     * @var int
     */
    protected int $priority = 10;

    /**
     * 检查是否支持指定的魔术方法
     *
     * @param string $magicMethod 魔术方法名
     * @return bool
     */
    public function supports(string $magicMethod): bool
    {
        return in_array($magicMethod, ['__call', '__callStatic']);
    }

    /**
     * 处理魔术方法调用
     *
     * @param object $instance 调用魔术方法的对象实例
     * @param array  $arguments 方法参数
     * @return mixed
     */
    public function handle(object $instance, array $arguments)
    {
        // 仅在 Laravel 环境下处理
        if (!FrameTypeUtil::isLaravel()) {
            return $this->continue();
        }

        $method     = $arguments[0];
        $parameters = $arguments[1] ?? [];
        $className  = get_class($instance);

        // 检查 app 函数是否存在
        if (!function_exists('app')) {
            return $this->continue();
        }

        // 检查 app 容器中是否有该类的实例
        if (!method_exists('app', 'has') || !app()->has($className)) {
            return $this->continue();
        }

        $appInstance = app($className);

        // 检查方法是否存在
        if (!method_exists($appInstance, $method)) {
            return $this->continue();
        }

        return $appInstance->$method(...$parameters);
    }
}
