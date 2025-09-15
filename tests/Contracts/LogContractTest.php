<?php

namespace Tests\Contracts;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Contracts\LogContract;

class LogContractTest extends TestCase
{
    /**
     * 测试LogContract接口的error方法
     */
    public function testLogErrorMethod()
    {
        $this->testLogWithContent('error', 'Error message', ['key' => 'value', 'error_code' => 500]);
    }

    /**
     * 测试LogContract接口的warning方法
     */
    public function testLogWarningMethod()
    {
        $this->testLogWithContent('warning', 'Warning message', ['level' => 'warning', 'details' => 'something to watch']);
    }

    /**
     * 测试LogContract接口的info方法
     */
    public function testLogInfoMethod()
    {
        $this->testLogWithContent('info', 'Info message', ['module' => 'user', 'action' => 'login']);
    }

    /**
     * 测试LogContract接口的debug方法
     */
    public function testLogDebugMethod()
    {
        $this->testLogWithContent('debug', 'Debug message', ['variable' => 'test', 'value' => 42, 'trace' => 'line 123']);
    }

    /**
     * 测试空数组内容
     */
    public function testLogWithEmptyContent()
    {
        $this->testLogWithContent('info', 'Test with empty content', []);
    }

    /**
     * 测试复杂数组内容
     */
    public function testLogWithComplexContent()
    {
        $complexContent = [
            'user' => [
                'id' => 123,
                'name' => 'John Doe',
                'roles' => ['admin', 'user']
            ],
            'timestamp' => time(),
            'data' => (object)['key' => 'value']
        ];
        
        $this->testLogWithContent('info', 'Test with complex content', $complexContent);
    }

    /**
     * 私有辅助方法，用于测试不同日志级别和内容
     *
     * @param string $method
     * @param string $message
     * @param array $content
     */
    private function testLogWithContent(string $method, string $message, array $content)
    {
        $logMock = $this->createMock(LogContract::class);
        
        $logMock->expects($this->once())
            ->method($method)
            ->with($message, $content);
        
        $logMock->{$method}($message, $content);
    }
}