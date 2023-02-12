<?php

namespace Rice\Basic\Contracts;

interface CacheContract
{
    public function set($key, $value);

    public function get($key);
}
