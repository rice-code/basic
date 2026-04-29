<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

interface HandlerInterface
{
    /**
     * 检查是否支持指定的魔术方法
     *
     * @param string $magicMethod 魔术方法名（如 '__call', '__get', '__set' 等）
     * @return bool
     */
    public function supports(string $magicMethod): bool;

    /**
     * 处理魔术方法调用
     *
     * @param object $instance 调用魔术方法的对象实例
     * @param array  $arguments 方法参数
     * @return mixed
     */
    public function handle(object $instance, array $arguments);

    /**
     * 获取处理器优先级（数字越小优先级越高）
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * 是否跳过父类方法的结果直接处理
     *
     * @return bool
     */
    public function skipParentResult(): bool;
}
