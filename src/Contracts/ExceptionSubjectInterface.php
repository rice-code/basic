<?php

namespace Rice\Basic\Contracts;

/**
 * 异常主题接口（被观察者）
 * 定义异常通知相关的方法.
 */
interface ExceptionSubjectInterface
{
    /**
     * 注册观察者.
     *
     * @param ExceptionObserverInterface $observer 观察者实例
     */
    public function attach(ExceptionObserverInterface $observer): void;

    /**
     * 移除观察者.
     *
     * @param ExceptionObserverInterface $observer 观察者实例
     */
    public function detach(ExceptionObserverInterface $observer): void;

    /**
     * 通知所有观察者.
     *
     * @param \Exception $e 异常实例
     */
    public function notify(\Exception $e): void;
}
