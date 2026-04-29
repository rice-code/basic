<?php

namespace Rice\Basic\Tests\Support\Utils;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Utils\StrUtil;

class StrUtilTest extends TestCase
{
    public function testIsUpper()
    {
        $this->assertTrue(StrUtil::isUpper('TEST'));
        $this->assertTrue(StrUtil::isUpper('A'));
        $this->assertFalse(StrUtil::isUpper('Test'));
        $this->assertFalse(StrUtil::isUpper('test'));
        $this->assertFalse(StrUtil::isUpper('123'));
    }

    public function testIsLower()
    {
        $this->assertTrue(StrUtil::isLower('test'));
        $this->assertTrue(StrUtil::isLower('a'));
        $this->assertFalse(StrUtil::isLower('Test'));
        $this->assertFalse(StrUtil::isLower('TEST'));
        $this->assertFalse(StrUtil::isLower('123'));
    }

    public function testCamelCaseToSnakeCase()
    {
        $this->assertEquals('test_string', StrUtil::camelCaseToSnakeCase('testString'));
        $this->assertEquals('test_string', StrUtil::camelCaseToSnakeCase('TestString'));
        $this->assertEquals('test', StrUtil::camelCaseToSnakeCase('test'));
    }

    public function testSnakeCaseToCamelCase()
    {
        $this->assertEquals('testString', StrUtil::snakeCaseToCamelCase('test_string'));
        $this->assertEquals('test', StrUtil::snakeCaseToCamelCase('test'));
        $this->assertEquals('testStringValue', StrUtil::snakeCaseToCamelCase('test_string_value'));
    }

    public function testStartsWith()
    {
        $this->assertTrue(StrUtil::startsWith('Hello World', 'Hello'));
        $this->assertTrue(StrUtil::startsWith('Hello World', ['Hello', 'Hi']));
        $this->assertFalse(StrUtil::startsWith('Hello World', 'World'));
        $this->assertFalse(StrUtil::startsWith('Hello World', ''));
    }

    public function testEndsWith()
    {
        $this->assertTrue(StrUtil::endsWith('Hello World', 'World'));
        $this->assertTrue(StrUtil::endsWith('Hello World', ['World', 'Universe']));
        $this->assertFalse(StrUtil::endsWith('Hello World', 'Hello'));
    }

    public function testClassBaseName()
    {
        $this->assertEquals('StrUtil', StrUtil::classBaseName(StrUtil::class));

        $obj = new \stdClass();
        $this->assertEquals('stdClass', StrUtil::classBaseName($obj));
    }
}
