<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * 用于测试AutoFillProperties trait的测试类
 */
class TestUser {
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
     * 获取name属性
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->name;
    }

    /**
     * 获取age属性
     *
     * @return int
     */
    public function getUserAge(): int
    {
        return $this->age;
    }
}

/**
 * 测试Laravel环境下的参数自动注入功能
 */
class LaravelAutoFillTest extends TestCase
{
    /**
     * 测试方法 - 模拟Laravel环境下的自动注入
     */
    public function testLaravelAutoFill()
    {
        // 测试断言1：通过autoFillInitialize方法初始化
        $params = ['name' => 'Test', 'age' => 25];
        $test = new TestUser();
        $test->autoFillInitialize($params);
        $this->assertEquals('Test', $test->getUserName());
        $this->assertEquals(25, $test->getUserAge());
        
        // 测试断言2：直接调用autoFillInitialize方法（替换initialize方法）
        $test2 = new TestUser();
        $test2->autoFillInitialize(['name' => 'Test2', 'age' => 30]);
        $this->assertEquals('Test2', $test2->getUserName());
        $this->assertEquals(30, $test2->getUserAge());
        
        // 测试断言3：直接调用autoFillInitialize方法
        $test3 = new TestUser();
        $test3->autoFillInitialize(['name' => 'Test3', 'age' => 35]);
        $this->assertEquals('Test3', $test3->getUserName());
        $this->assertEquals(35, $test3->getUserAge());
    }
}

// 运行测试
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    LaravelAutoFillTest::testLaravelAutoFill();
}