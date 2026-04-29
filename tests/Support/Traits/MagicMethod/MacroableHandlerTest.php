<?php

namespace Rice\Basic\Tests\Support\Traits\MagicMethod;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\MagicMethod\MacroableHandler;

class MacroableHandlerTest extends TestCase
{
    public function testSupportsCallMethods()
    {
        $handler = new MacroableHandler();

        $this->assertTrue($handler->supports('__call'));
        $this->assertTrue($handler->supports('__callStatic'));
        $this->assertFalse($handler->supports('__get'));
    }

    public function testPriority()
    {
        $handler = new MacroableHandler();

        $this->assertEquals(99, $handler->getPriority());
    }
}
