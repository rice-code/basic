<?php

namespace Rice\Basic\Tests\Support\Traits\MagicMethod;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\MagicMethod\DefaultGetHandler;

class DefaultGetHandlerTest extends TestCase
{
    public function testSupportsGetMethod()
    {
        $handler = new DefaultGetHandler();

        $this->assertTrue($handler->supports('__get'));
        $this->assertFalse($handler->supports('__set'));
        $this->assertFalse($handler->supports('__call'));
    }

    public function testPriority()
    {
        $handler = new DefaultGetHandler();

        $this->assertEquals(50, $handler->getPriority());
    }

    public function testHandleExistingProperty()
    {
        $handler = new DefaultGetHandler();
        $instance = (object) ['name' => 'test'];

        $result = $handler->handle($instance, ['name']);

        $this->assertEquals('test', $result);
    }

    public function testHandleNonExistingProperty()
    {
        $handler = new DefaultGetHandler();
        $instance = (object) [];

        $result = $handler->handle($instance, ['nonexistent']);

        $this->assertEquals(\Rice\Basic\Support\Traits\MagicMethod\AbstractHandler::MAGIC_METHOD_CONTINUE, $result);
    }
}
