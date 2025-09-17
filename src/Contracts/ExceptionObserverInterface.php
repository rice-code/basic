<?php

namespace Rice\Basic\Contracts;

/**
 * 异常观察者接口
 * 定义异常处理逻辑.
 */
interface ExceptionObserverInterface
{
    /**
     * 处理异常.
     *
     * @param \Exception $e 异常实例
     */
    public function handle(\Exception $e): void;
}
