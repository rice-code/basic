<?php

namespace Tests\Contracts;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Contracts\LogContract;

class LogContractTest extends TestCase
{
    /**
     * 测试LogContract接口的error方法.
     */
    public function testLogErrorMethod(): void
    {
        $this->testLogWithContent('error', 'Error message', ['key' => 'value', 'error_code' => 500]);
    }

    /**
     * 测试LogContract接口的warning方法.
     */
    public function testLogWarningMethod(): void
    {
        $content = ['level' => 'warning', 'details' => 'something to watch'];
        $this->testLogWithContent('warning', 'Warning message', $content);
    }

    /**
     * 测试LogContract接口的info方法.
     */
    public function testLogInfoMethod(): void
    {
        $this->testLogWithContent('info', 'Info message', ['module' => 'user', 'action' => 'login']);
    }

    /**
     * 测试LogContract接口的debug方法.
     */
    public function testLogDebugMethod(): void
    {
        $content = ['variable' => 'test', 'value' => 42, 'trace' => 'line 123'];
        $this->testLogWithContent('debug', 'Debug message', $content);
    }

    /**
     * 测试空数组内容.
     */
    public function testLogWithEmptyContent(): void
    {
        $this->testLogWithContent('info', 'Test with empty content', []);
    }

    /**
     * 测试复杂数组内容.
     */
    public function testLogWithComplexContent(): void
    {
        $complexContent = [
            'user' => [
                'id'    => 123,
                'name'  => 'John Doe',
                'roles' => ['admin', 'user'],
            ],
            'timestamp' => time(),
            'data'      => (object) ['key' => 'value'],
        ];

        $this->testLogWithContent('info', 'Test with complex content', $complexContent);
    }

    /**
     * 私有辅助方法，用于测试不同日志级别和内容.
     *
     * @param string $method
     * @param string $message
     * @param array  $content
     */
    private function testLogWithContent(string $method, string $message, array $content): void
    {
        $logMock = $this->createMock(LogContract::class);

        $logMock->expects($this->once())
            ->method($method)
            ->with($message, $content);

        $logMock->{$method}($message, $content);
    }
}
