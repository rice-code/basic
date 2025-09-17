<?php

namespace Tests\Support\Traits;

use PHPUnit\Framework\TestCase;

class AutoFillPropertiesTest extends TestCase
{
    /**
     * 测试无参数实例化.
     */
    public function testInstantiation(): void
    {
        $instance = new MinimalTestClass();

        // 验证对象可以成功实例化
        $this->assertInstanceOf(MinimalTestClass::class, $instance);

        // 验证内部参数尚未初始化
        $this->assertIsArray($instance->getParams());
        $this->assertEmpty($instance->getParams());
    }

    /**
     * 测试通过autoFillInitialize方法设置参数.
     */
    public function testAutoFillInitializeMethod(): void
    {
        // 无参数实例化
        $instance = new MinimalTestClass();

        // 验证初始状态
        $this->assertIsArray($instance->getParams());
        $this->assertEmpty($instance->getParams());

        // 通过autoFillInitialize方法设置参数
        $data  = ['name' => 'Laravel', 'version' => 8];
        $cache = new MockCache();
        $instance->autoFillInitialize($data, $cache);

        // 验证参数是否正确设置
        $this->assertEquals($data, $instance->getParams());
        $this->assertEquals($cache, $instance->getCache());
    }

    /**
     * 测试不同类型的参数处理.
     */
    public function testAutoFillWithDifferentParamTypes(): void
    {
        $instance = new MinimalTestClass();

        // 测试字符串JSON参数
        $json = '{"name":"Test","id":123}';
        $instance->autoFillInitialize($json);
        $this->assertEquals(['name' => 'Test', 'id' => 123], $instance->getParams());

        // 测试对象参数
        $obj       = (object) ['key' => 'value', 'num' => 456];
        $instance2 = new MinimalTestClass();
        $instance2->autoFillInitialize($obj);
        $this->assertEquals(['key' => 'value', 'num' => 456], $instance2->getParams());
    }

    /**
     * 测试onlyCurrentClass设置功能.
     */
    public function testOnlyCurrentClassSetting(): void
    {
        $instance = new MinimalTestClass();

        // 验证默认值
        $this->assertFalse($instance->isOnlyCurrentClass());

        // 测试设置为true
        $instance->setOnlyCurrentClass(true);
        $this->assertTrue($instance->isOnlyCurrentClass());

        // 测试设置为false
        $instance->setOnlyCurrentClass(false);
        $this->assertFalse($instance->isOnlyCurrentClass());

        // 测试链式调用
        $instance->setOnlyCurrentClass(true)->autoFillInitialize(['name' => 'Test']);
        $this->assertTrue($instance->isOnlyCurrentClass());
    }
}
