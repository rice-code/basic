<?php

namespace Tests\Support\Traits;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * 用于测试AutoFillProperties trait的最小化测试类
 */
class MinimalTestClass
{
    use AutoFillProperties;
    
    // 用于测试参数是否被正确设置到内部变量
    public function getParams()
    {
        try {
            return $this->getAutoFillHandler()->getParams();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    public function getProperties()
    {
        try {
            return $this->getAutoFillHandler()->getProperties();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    public function getAlias()
    {
        try {
            return $this->getAutoFillHandler()->getAlias();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    public function getCache()
    {
        try {
            return $this->getAutoFillHandler()->getCache();
        } catch (\Exception $e) {
            return null;
        }
    }
}

/**
 * 模拟的缓存实现类
 */
class MockCache implements \Rice\Basic\Contracts\CacheContract
{
    public function set($key, $value)
    {
        return true;
    }
    
    public function get($key, $default = null)
    {
        return $default;
    }
}

class AutoFillPropertiesTest extends TestCase
{
    /**
     * 测试无参数实例化
     */
    public function testInstantiation()
    {
        $instance = new MinimalTestClass();
        
        // 验证对象可以成功实例化
        $this->assertInstanceOf(MinimalTestClass::class, $instance);
        
        // 验证内部参数尚未初始化
        $this->assertIsArray($instance->getParams());
        $this->assertEmpty($instance->getParams());
    }
    
    /**
     * 测试通过autoFillInitialize方法设置参数
     */
    public function testAutoFillInitializeMethod()
    {
        // 无参数实例化
        $instance = new MinimalTestClass();
        
        // 验证初始状态
        $this->assertIsArray($instance->getParams());
        $this->assertEmpty($instance->getParams());
        
        // 通过autoFillInitialize方法设置参数
        $data = ['name' => 'Laravel', 'version' => 8];
        $cache = new MockCache();
        $instance->autoFillInitialize($data, $cache);
        
        // 验证参数是否正确设置
        $this->assertEquals($data, $instance->getParams());
        $this->assertEquals($cache, $instance->getCache());
    }
    
    /**
     * 测试不同类型的参数处理
     */
    public function testAutoFillWithDifferentParamTypes()
    {
        $instance = new MinimalTestClass();
        
        // 测试字符串JSON参数
        $json = '{"name":"Test","id":123}';
        $instance->autoFillInitialize($json);
        $this->assertEquals(['name' => 'Test', 'id' => 123], $instance->getParams());
        
        // 测试对象参数
        $obj = (object)['key' => 'value', 'num' => 456];
        $instance2 = new MinimalTestClass();
        $instance2->autoFillInitialize($obj);
        $this->assertEquals(['key' => 'value', 'num' => 456], $instance2->getParams());
    }
    
    /**
     * 测试onlyCurrentClass设置功能
     */
    public function testOnlyCurrentClassSetting()
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