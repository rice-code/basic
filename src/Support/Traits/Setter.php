<?php

namespace Rice\Basic\Support\Traits;

trait Setter
{
    protected function resetAccessor(): void
    {
        $this->_setter = true;
        $this->_getter = false;
    }
}
