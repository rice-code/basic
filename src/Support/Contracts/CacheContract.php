<?php

namespace Rice\Basic\Support\Contracts;

interface CacheContract
{
    public function set($key, $value);

    public function get($key);
}
