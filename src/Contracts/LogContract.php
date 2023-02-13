<?php

namespace Rice\Basic\Contracts;

interface LogContract
{
    public static function error(string $message, array $content);
    public static function warning(string $message, array $content);
    public static function info(string $message, array $content);

    public static function debug(string $message, array $content);
}