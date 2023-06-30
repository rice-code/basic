<?php

namespace Rice\Basic\Support\Abstracts\Guzzle;

use Rice\Basic\Support\Loggers\LaravelLog;

abstract class LaravelClient extends GuzzleClient
{
    public static function build(): self
    {
        return new static(LaravelLog::build());
    }
}
