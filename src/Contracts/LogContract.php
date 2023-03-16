<?php

namespace Rice\Basic\Contracts;

interface LogContract
{
    public function error(string $message, array $content): void;
    public function warning(string $message, array $content): void;
    public function info(string $message, array $content): void;

    public function debug(string $message, array $content): void;
}