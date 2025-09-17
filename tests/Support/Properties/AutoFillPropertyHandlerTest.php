<?php

namespace Tests\Support\Properties;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Properties\AutoFillPropertyHandler;

/**
 * 测试AutoFillPropertyHandler类的独立使用.
 */
class AutoFillPropertyHandlerTest extends TestCase
{
    /**
     * 测试独立使用AutoFillPropertyHandler类.
     */
    public function testAutoFillPropertyHandler(): void
    {
        // 创建目标对象
        $testUser = new TestUserHandler();

        // 创建处理器
        $handler = new AutoFillPropertyHandler($testUser);

        // 准备参数
        $params = ['name' => 'HandlerTest', 'age' => 28];

        // 执行初始化和填充
        $handler->initialize($params);

        // 验证属性填充是否成功
        $this->assertEquals('HandlerTest', $testUser->getUserName());
        $this->assertEquals(28, $testUser->getUserAge());

        // 测试单独调用fill方法
        $handler->initialize(['name' => 'HandlerTest2', 'age' => 30]);
        $this->assertEquals('HandlerTest2', $testUser->getUserName());
        $this->assertEquals(30, $testUser->getUserAge());

        // 测试空参数情况
        $testUser2 = new TestUserHandler();
        $handler2  = new AutoFillPropertyHandler($testUser2);
        $handler2->initialize([]);
        $this->assertEquals('', $testUser2->getUserName());
        $this->assertEquals(0, $testUser2->getUserAge());
    }
}
