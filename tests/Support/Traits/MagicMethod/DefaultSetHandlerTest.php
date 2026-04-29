<?php

namespace Rice\Basic\Tests\Support\Traits\MagicMethod;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\MagicMethod\DefaultSetHandler;

class DefaultSetHandlerTest extends TestCase
{
    public function testSupportsSetMethod()
    {
        $handler = new DefaultSetHandler();

        $this->assertTrue($handler->supports('__set'));
        $this->assertFalse($handler->supports('__get'));
        $this->assertFalse($handler->supports('__call'));
    }

    public function testPriority()
    {
        $handler = new DefaultSetHandler();

        $this->assertEquals(50, $handler->getPriority());
    }

    public function testHandleSetProperty()
    {
        $handler = new DefaultSetHandler();
        $instance = (object) [];

        $result = $handler->handle($instance, ['name', 'test']);

        $this->assertTrue($result);
        $this->assertEquals('test', $instance->name);
    }

    public function testHandleNonAccessibleProperty()
    {
        $handler = new DefaultSetHandler();

        // 使用一个有私有属性的类
        $instance = new class {
            private $privateProp;
        };

        $result = $handler->handle($instance, ['privateProp', 'value']);

        $this->assertEquals(\Rice\Basic\Support\Traits\MagicMethod\AbstractHandler::MAGIC_METHOD_CONTINUE, $result);
    }
}
