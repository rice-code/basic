<?php

namespace Tests\Support\Properties;

/**
 * 用于测试AutoFillPropertyHandler类的独立功能.
 */
class TestUserHandler
{
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
