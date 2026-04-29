<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * 魔术方法调用继续标记
     *
     * @var string
     */
    public const MAGIC_METHOD_CONTINUE = '__MAGIC_METHOD_CONTINUE__';

    /**
     * 优先级值，数字越小优先级越高
     *
     * @var int
     */
    protected int $priority = 100;

    /**
     * 是否跳过父类方法的结果
     *
     * @var bool
     */
    protected bool $skipParentResult = false;

    /**
     * 获取处理器优先级
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * 是否跳过父类方法的结果直接处理
     *
     * @return bool
     */
    public function skipParentResult(): bool
    {
        return $this->skipParentResult;
    }

    /**
     * 返回继续标记，让下一个处理器处理
     *
     * @return string
     */
    protected function continue(): string
    {
        return self::MAGIC_METHOD_CONTINUE;
    }
}
