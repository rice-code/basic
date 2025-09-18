<?php

namespace Rice\Basic\Support\Observers;

use Rice\Basic\Contracts\LogContract;
use Rice\Basic\Support\TraceIdManager;
use Rice\Basic\Support\Loggers\LaravelLog;
use Rice\Basic\Contracts\ExceptionObserverInterface;

/**
 * 异常日志观察者
 * 负责记录异常日志信息.
 */
class ExceptionLogObserver implements ExceptionObserverInterface
{
    /**
     * 日志实例.
     *
     * @var LogContract
     */
    protected $log;

    /**
     * ExceptionLogObserver constructor.
     *
     * @param LogContract|null $log 日志实例，如果为null则使用默认的LaravelLog
     */
    public function __construct(LogContract $log = null)
    {
        $this->log = $log ?: LaravelLog::build();
    }

    /**
     * {@inheritDoc}
     */
    public function handle(\Exception $e): void
    {
        // 获取当前的追踪ID
        $traceId = TraceIdManager::getInstance()->getTraceId();

        // 构建日志内容，包含追踪ID
        $content = [
            'trace_id' => $traceId,
            'code'     => $e->getCode(),
            'message'  => $e->getMessage(),
            'file'     => $e->getFile(),
            'line'     => $e->getLine(),
            'trace'    => $e->getTraceAsString(),
        ];

        // 记录异常日志，在消息中也包含追踪ID
        $this->log->error('Exception occurred: ' . $e->getMessage() . ' [trace_id: ' . $traceId . ']', $content);
    }
}
