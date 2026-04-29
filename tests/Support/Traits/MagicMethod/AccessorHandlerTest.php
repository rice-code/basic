<?php

namespace Rice\Basic\Tests\Support\Traits\MagicMethod;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\MagicMethod\AccessorHandler;

class AccessorHandlerTest extends TestCase
{
    public function testSupportsCallMethod()
    {
        $handler = new AccessorHandler();

        $this->assertTrue($handler->supports('__call'));
        $this->assertFalse($handler->supports('__get'));
        $this->assertFalse($handler->supports('__set'));
    }

    public function testPriority()
    {
        $handler = new AccessorHandler();

        $this->assertEquals(100, $handler->getPriority());
    }

    public function testHandleGetMethod()
    {
        $handler = new AccessorHandler();

        $instance = new class {
            protected $name = 'test';

            public function getAuth()
            {
                return '/^(get|set)([A-Z][a-zA-Z0-9]*)$/';
            }

            public function getValue($attrName)
            {
                return $this->{$attrName};
            }
        };

        $result = $handler->handle($instance, ['getName', []]);

        $this->assertEquals('test', $result);
    }

    public function testHandleSetMethod()
    {
        $handler = new AccessorHandler();

        $instance = new class {
            protected $name;

            public function getAuth()
            {
                return '/^(get|set)([A-Z][a-zA-Z0-9]*)$/';
            }

            public function setValue($attrName, $value)
            {
                $this->{$attrName} = $value[0];
            }
        };

        $result = $handler->handle($instance, ['setName', ['newValue']]);

        $this->assertEquals($instance, $result);
    }

    public function testHandleNonMatchingMethod()
    {
        $handler = new AccessorHandler();

        $instance = new class {
            public function getAuth()
            {
                return '/^(get|set)([A-Z][a-zA-Z0-9]*)$/';
            }
        };

        $result = $handler->handle($instance, ['nonMatchingMethod', []]);

        $this->assertEquals(\Rice\Basic\Support\Traits\MagicMethod\AbstractHandler::MAGIC_METHOD_CONTINUE, $result);
    }
}
