<?php

namespace Rice\Basic\Support;

use Rice\Basic\Contracts\LogContract;
use Rice\Basic\Support\Loggers\LaravelLog;

/**
 * 日志追踪门面类
 * 提供带有追踪ID的日志记录功能，简化跨系统的请求追踪
 * 支持在所有日志中自动包含当前请求的追踪ID.
 */
class LogTraceFacade
{
    /**
     * 日志实例.
     *
     * @var LogContract
     */
    private static $logInstance;

    /**
     * 设置日志实例
     * 允许自定义日志实现.
     *
     * @param LogContract $log 日志实例
     */
    public static function setLogInstance(LogContract $log): void
    {
        self::$logInstance = $log;
    }

    /**
     * 获取日志实例
     * 如果尚未设置，则创建默认的LaravelLog实例.
     *
     * @return LogContract 日志实例
     */
    private static function getLogInstance(): LogContract
    {
        if (is_null(self::$logInstance)) {
            self::$logInstance = LaravelLog::build();
        }

        return self::$logInstance;
    }

    /**
     * 记录错误日志
     * 自动包含当前请求的追踪ID.
     *
     * @param string $message 日志消息
     * @param array  $content 附加内容
     */
    public static function error(string $message, array $content = []): void
    {
        $contentWithTrace = self::addTraceIdToContent($content);
        self::getLogInstance()->error($message . self::getTraceIdSuffix(), $contentWithTrace);
    }

    /**
     * 记录警告日志
     * 自动包含当前请求的追踪ID.
     *
     * @param string $message 日志消息
     * @param array  $content 附加内容
     */
    public static function warning(string $message, array $content = []): void
    {
        $contentWithTrace = self::addTraceIdToContent($content);
        self::getLogInstance()->warning($message . self::getTraceIdSuffix(), $contentWithTrace);
    }

    /**
     * 记录信息日志
     * 自动包含当前请求的追踪ID.
     *
     * @param string $message 日志消息
     * @param array  $content 附加内容
     */
    public static function info(string $message, array $content = []): void
    {
        $contentWithTrace = self::addTraceIdToContent($content);
        self::getLogInstance()->info($message . self::getTraceIdSuffix(), $contentWithTrace);
    }

    /**
     * 记录调试日志
     * 自动包含当前请求的追踪ID.
     *
     * @param string $message 日志消息
     * @param array  $content 附加内容
     */
    public static function debug(string $message, array $content = []): void
    {
        $contentWithTrace = self::addTraceIdToContent($content);
        self::getLogInstance()->debug($message . self::getTraceIdSuffix(), $contentWithTrace);
    }

    /**
     * 向日志内容中添加追踪ID.
     *
     * @param array $content 原始日志内容
     * @return array 添加了追踪ID的日志内容
     */
    private static function addTraceIdToContent(array $content): array
    {
        $traceId             = TraceIdManager::getInstance()->getTraceId();
        $content['trace_id'] = $traceId;

        return $content;
    }

    /**
     * 获取日志消息后缀，包含追踪ID.
     *
     * @return string 包含追踪ID的后缀
     */
    private static function getTraceIdSuffix(): string
    {
        return ' [trace_id: ' . TraceIdManager::getInstance()->getTraceId() . ']';
    }

    /**
     * 重置日志实例
     * 通常用于测试环境.
     */
    public static function reset(): void
    {
        self::$logInstance = null;
    }
}
