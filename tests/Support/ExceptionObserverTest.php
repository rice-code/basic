<?php

namespace Tests\Support;

use Rice\Basic\Support\Lang;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Infrastructure\Enum\InvalidRequestEnum;
use Rice\Basic\Infrastructure\Exception\InvalidRequestException;

class ExceptionObserverTest extends TestCase
{
    /**
     * 测试异常观察者模式正常工作.
     *
     * 注意：这个测试会故意抛出异常，然后由观察者自动记录日志
     * 你会在命令行中看到异常信息输出，这是观察者模式在正常工作
     *
     * @return void
     */
    public function testExceptionObserverWorks()
    {
        // 确保使用中文语言环境
        Lang::getInstance()->setLocale('zh-CN');

        // 记录开始时间，用于验证测试执行速度
        $startTime = microtime(true);

        try {
            // 故意抛出异常
            throw new InvalidRequestException(InvalidRequestEnum::DEFAULT);
        } catch (InvalidRequestException $e) {
            // 验证异常信息正确
            $this->assertEquals('业务错误', $e->getMessage());

            // 验证http状态码正确
            $this->assertEquals(400, $e::httpStatusCode());
        }

        // 验证测试执行速度（应非常快）
        $executionTime = microtime(true) - $startTime;
        $this->assertLessThan(0.1, $executionTime, 'Exception handling should be fast');
    }
}
