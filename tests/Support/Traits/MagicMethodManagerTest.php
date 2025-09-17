<?php

namespace Tests\Support\Traits;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\Macroable;

/**
 * 用于测试的简单类，同时模拟Accessor和Macroable功能.
 */
class TestPriorityClass
{
    use Accessor;
    use Macroable;

    protected $name = 'Default Name';
}

/**
 * MagicMethodManager测试类
 * 用于测试魔术方法处理器的优先级修复.
 */
class MagicMethodManagerTest extends TestCase
{
    /**
     * 测试魔术方法处理器优先级修复
     * 验证Macroable处理器(优先级101)是否优先于Accessor处理器(优先级100).
     */
    public function testPriorityFix()
    {
        // 创建测试实例
        $test = new TestPriorityClass();

        // 1. 测试Accessor功能正常工作
        $test->setName('测试名称');
        $this->assertEquals('测试名称', $test->getName(), 'Accessor功能不正常');

        // 2. 注册一个普通宏方法并测试
        TestPriorityClass::registerMacro('testMacro', function ($param) {
            return "宏方法被调用，参数: {$param}";
        });

        $result = $test->testMacro('hello');
        $this->assertEquals('宏方法被调用，参数: hello', $result, '普通宏方法调用失败');

        // 3. 注册一个与Accessor格式冲突的宏方法（getter格式）
        $testValue = '优先级测试值';
        TestPriorityClass::registerMacro('getTestPriority', function () use ($testValue) {
            return $testValue;
        });

        // 4. 测试冲突情况 - 应该优先调用Macroable处理器(优先级101)而不是Accessor处理器(优先级100)
        $conflictResult = $test->getTestPriority();
        $this->assertEquals($testValue, $conflictResult, '优先级修复失败！Macroable处理器未优先执行');

        // 5. 注册另一个冲突宏方法（setter格式）
        $setterTestValue = '设置值测试';
        TestPriorityClass::registerMacro('setTestPriority', function ($value) use (&$setterTestValue) {
            $setterTestValue = $value;

            return $this;
        });

        // 6. 测试setter格式的冲突情况
        $test->setTestPriority('新的测试值');
        $this->assertEquals('新的测试值', $setterTestValue, 'setter格式的优先级修复失败');
    }
}
