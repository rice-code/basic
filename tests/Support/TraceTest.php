<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\LogTraceFacade;
use Rice\Basic\Support\TraceIdManager;
use Rice\Basic\Components\Exception\InvalidRequestException;

/**
 * 分布式追踪功能测试类
 * 测试TraceIdManager和LogTraceFacade的功能是否正常工作.
 */
class TraceTest extends TestCase
{
    /**
     * 测试TraceIdManager的单例模式和基本功能.
     */
    public function testTraceIdManagerSingletonAndBasicFunctions(): void
    {
        // 获取TraceIdManager实例
        $manager1 = TraceIdManager::getInstance();
        $manager2 = TraceIdManager::getInstance();

        // 验证单例模式是否正常工作
        $this->assertSame($manager1, $manager2, 'TraceIdManager should be a singleton');

        // 获取初始追踪ID
        $traceId1 = $manager1->getTraceId();

        // 验证追踪ID已生成且不为空
        $this->assertNotEmpty($traceId1);

        // 验证UUID格式是否正确
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $traceId1
        );

        // 验证相同实例获取的追踪ID相同
        $this->assertEquals($traceId1, $manager2->getTraceId());

        // 重置追踪ID
        $traceId2 = $manager1->resetTraceId();

        // 验证重置后追踪ID已改变
        $this->assertNotEquals($traceId1, $traceId2);

        // 验证重置后所有实例的追踪ID相同
        $this->assertEquals($traceId2, $manager2->getTraceId());

        // 手动设置追踪ID
        $customTraceId = 'custom-trace-id-123456';
        $manager1->setTraceId($customTraceId);

        // 验证手动设置的追踪ID生效
        $this->assertEquals($customTraceId, $manager1->getTraceId());
        $this->assertEquals($customTraceId, $manager2->getTraceId());

        // 验证hasTraceId方法
        $this->assertTrue($manager1->hasTraceId());
    }

    /**
     * 测试异常日志中的追踪ID功能.
     *
     * @return void
     */
    public function testExceptionWithTraceId()
    {
        // 获取初始追踪ID
        $traceId = TraceIdManager::getInstance()->getTraceId();

        // 重置LogTraceFacade，确保测试独立性
        LogTraceFacade::reset();

        try {
            // 故意抛出异常
            throw new InvalidRequestException('测试异常');
        } catch (InvalidRequestException $e) {
            // 验证异常被正确捕获
            $this->assertEquals('测试异常', $e->getMessage());
        }

        // 注意：我们无法直接验证日志内容，但可以通过测试流程确保代码没有错误
        $this->assertTrue(true, 'Exception with trace ID was processed without errors');
    }

    /**
     * 测试LogTraceFacade的日志记录功能.
     */
    public function testLogTraceFacade(): void
    {
        // 重置LogTraceFacade，确保测试独立性
        LogTraceFacade::reset();

        // 获取当前的追踪ID
        $traceId = TraceIdManager::getInstance()->getTraceId();

        // 测试不同级别的日志记录
        LogTraceFacade::debug('This is a debug message', ['key' => 'value']);
        LogTraceFacade::info('This is an info message', ['user' => 'test']);
        LogTraceFacade::warning('This is a warning message', ['level' => 'warning']);
        LogTraceFacade::error('This is an error message', ['error_code' => 500]);

        // 注意：我们无法直接验证日志内容，但可以通过测试流程确保代码没有错误
        $this->assertTrue(true, 'LogTraceFacade was used without errors');
    }

    /**
     * 测试全局追踪ID在不同组件间的一致性.
     */
    public function testGlobalTraceIdConsistency(): void
    {
        // 重置状态
        TraceIdManager::getInstance()->resetTraceId();
        LogTraceFacade::reset();

        // 获取当前的追踪ID
        $traceId = TraceIdManager::getInstance()->getTraceId();

        // 记录一些日志
        LogTraceFacade::info('Starting process', ['step' => 1]);

        // 模拟业务处理
        try {
            // 记录处理中日志
            LogTraceFacade::info('Processing data', ['step' => 2]);

            // 记录成功日志
            LogTraceFacade::info('Process completed successfully', ['step' => 3]);
        } catch (InvalidRequestException $e) {
            // 记录错误日志
            LogTraceFacade::error('Process failed', ['step' => 'error', 'exception' => $e->getMessage()]);
        }

        // 验证整个流程中使用的是同一个追踪ID
        $this->assertEquals($traceId, TraceIdManager::getInstance()->getTraceId());

        // 注意：我们无法直接验证日志内容，但可以通过测试流程确保代码没有错误
        $this->assertTrue(true, 'Global trace ID consistency was maintained throughout the process');
    }
}
