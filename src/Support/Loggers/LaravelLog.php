<?php

namespace Rice\Basic\Support\Loggers;

use Rice\Basic\Contracts\LogContract;

class LaravelLog implements LogContract
{
    private $instance;
    public function error(string $message, array $content): void
    {
        $this->instance->error($message, $content);
    }

    public function warning(string $message, array $content): void
    {
        $this->instance->warning($message, $content);
    }

    public function info(string $message, array $content): void
    {
        $this->instance->info($message, $content);
    }

    public function debug(string $message, array $content): void
    {
        $this->instance->debug($message, $content);
    }

    public static function build(): LaravelLog
    {
        $log = (new self());
        // app function
        $log->instance = app('log');
        return $log;
    }
}