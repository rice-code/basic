<?php

namespace Rice\Basic\Support\Loggers;

use Rice\Basic\Contracts\LogContract;
use Rice\Basic\Support\Utils\FrameTypeUtil;

class LaravelLog implements LogContract
{
    /**
     * 日志实例.
     *
     * @var mixed
     */
    private $instance;

    /**
     * 日志文件路径.
     *
     * @var string
     */
    private $logFilePath;

    /**
     * 构造函数.
     */
    public function __construct()
    {
        // 设置默认日志文件路径
        $this->logFilePath = __DIR__ . '/../../../storage/logs/basic.log';
        // 确保日志目录存在
        $logDir = dirname($this->logFilePath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function error(string $message, array $content): void
    {
        if (is_callable([$this->instance, 'error'])) {
            $this->instance->error($message, $content);
        } else {
            $this->logToFile('[ERROR] ' . $message . ' ' . json_encode($content, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function warning(string $message, array $content): void
    {
        if (is_callable([$this->instance, 'warning'])) {
            $this->instance->warning($message, $content);
        } else {
            $this->logToFile('[WARNING] ' . $message . ' ' . json_encode($content, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function info(string $message, array $content): void
    {
        if (is_callable([$this->instance, 'info'])) {
            $this->instance->info($message, $content);
        } else {
            $this->logToFile('[INFO] ' . $message . ' ' . json_encode($content, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function debug(string $message, array $content): void
    {
        if (is_callable([$this->instance, 'debug'])) {
            $this->instance->debug($message, $content);
        } else {
            $this->logToFile('[DEBUG] ' . $message . ' ' . json_encode($content, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * 将日志写入文件.
     *
     * @param string $logMessage 日志消息
     */
    private function logToFile(string $logMessage): void
    {
        // 添加时间戳
        $timestamp        = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$logMessage}" . PHP_EOL;

        // 写入文件，避免输出到命令行
        file_put_contents($this->logFilePath, $formattedMessage, FILE_APPEND);
    }

    /**
     * 创建LaravelLog实例.
     *
     * @return self
     */
    public static function build(): self
    {
        $log = new self();

        // 检查是否在Laravel环境中
        if (FrameTypeUtil::isLaravel() && function_exists('app')) {
            // Laravel环境下使用app('log')获取日志实例
            $log->instance = app('log');
        } else {
            // 非Laravel环境下设置为null，日志方法会使用备用实现
            $log->instance = null;
        }

        return $log;
    }
}
