<?php

namespace Rice\Basic\Support\Traits;

trait Getter
{
    protected function resetAccessor(): void
    {
        $this->_setter = false;
        $this->_getter = true;
    }
}