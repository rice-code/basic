<?php

namespace Tests\Contracts;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Contracts\CacheContract;

class CacheContractTest extends TestCase
{
    /**
     * 测试CacheContract接口的set和get方法对不同数据类型的支持
     */
    public function testCacheSetAndGetWithString()
    {
        $this->testCacheWithDataType('test_string', 'string_value');
    }

    public function testCacheSetAndGetWithInteger()
    {
        $this->testCacheWithDataType('test_integer', 12345);
    }

    public function testCacheSetAndGetWithArray()
    {
        $this->testCacheWithDataType('test_array', ['key' => 'value', 'num' => 123]);
    }

    public function testCacheSetAndGetWithObject()
    {
        $obj = (object)['name' => 'test', 'id' => 42];
        $this->testCacheWithDataType('test_object', $obj);
    }

    public function testCacheSetAndGetWithNull()
    {
        $this->testCacheWithDataType('test_null', null);
    }

    /**
     * 测试CacheContract接口的get方法使用默认值
     */
    public function testCacheGetWithDefaultValue()
    {
        $cacheMock = $this->createMock(CacheContract::class);
        $defaultValue = 'default_value';
        
        $cacheMock->expects($this->once())
            ->method('get')
            ->with('non_existent_key', $defaultValue)
            ->willReturn($defaultValue);
        
        $result = $cacheMock->get('non_existent_key', $defaultValue);
        $this->assertEquals($defaultValue, $result);
    }

    /**
     * 私有辅助方法，用于测试不同数据类型的缓存操作
     *
     * @param string $key
     * @param mixed $value
     */
    private function testCacheWithDataType(string $key, $value)
    {
        $cacheMock = $this->createMock(CacheContract::class);
        
        $cacheMock->expects($this->once())
            ->method('set')
            ->with($key, $value)
            ->willReturn(true);
        
        $cacheMock->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($value);
        
        $cacheMock->set($key, $value);
        $result = $cacheMock->get($key);
        
        if (is_object($value)) {
            $this->assertEquals(get_class($value), get_class($result));
            $this->assertEquals(json_encode($value), json_encode($result));
        } else {
            $this->assertEquals($value, $result);
        }
    }
}