<?php

namespace Rice\Basic\Support\Contracts;

interface AutoFillCacheContract {
    public function set($key, $value);

    public function get($key);
}
