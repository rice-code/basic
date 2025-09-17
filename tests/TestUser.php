<?php

namespace Tests;

use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * 用于测试AutoFillProperties trait的测试类.
 */
class TestUser
{
    use AutoFillProperties;

    /**
     * @var string
     */
    private string $name = '';

    /**
     * @var int
     */
    private int $age = 0;

    /**
     * 获取name属性.
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->name;
    }

    /**
     * 获取age属性.
     *
     * @return int
     */
    public function getUserAge(): int
    {
        return $this->age;
    }
}
