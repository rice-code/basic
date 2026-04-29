<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

class DefaultGetHandler extends AbstractHandler
{
    /**
     * 优先级：50（较高优先级）
     *
     * @var int
     */
    protected int $priority = 50;

    /**
     * 检查是否支持指定的魔术方法
     *
     * @param string $magicMethod 魔术方法名
     * @return bool
     */
    public function supports(string $magicMethod): bool
    {
        return $magicMethod === '__get';
    }

    /**
     * 处理 __get 魔术方法调用
     *
     * @param object $instance 调用魔术方法的对象实例
     * @param array  $arguments 方法参数
     * @return mixed
     */
    public function handle(object $instance, array $arguments)
    {
        $name = $arguments[0];

        try {
            return $instance->{$name};
        } catch (\Throwable $e) {
            // 如果直接获取失败，返回继续标记，让其他处理器有机会处理
            return $this->continue();
        }
    }
}
