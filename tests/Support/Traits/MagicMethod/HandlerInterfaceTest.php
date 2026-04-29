<?php

namespace Rice\Basic\Tests\Support\Traits\MagicMethod;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Traits\MagicMethod\AbstractHandler;

class HandlerInterfaceTest extends TestCase
{
    public function testAbstractHandlerPriority()
    {
        $handler = new class extends AbstractHandler {
            public function supports(string $magicMethod): bool
            {
                return $magicMethod === '__get';
            }

            public function handle(object $instance, array $arguments)
            {
                return $this->continue();
            }
        };

        $this->assertEquals(100, $handler->getPriority());
        $this->assertFalse($handler->skipParentResult());
    }

    public function testAbstractHandlerCustomPriority()
    {
        $handler = new class extends AbstractHandler {
            protected int $priority = 50;

            public function supports(string $magicMethod): bool
            {
                return $magicMethod === '__get';
            }

            public function handle(object $instance, array $arguments)
            {
                return $this->continue();
            }
        };

        $this->assertEquals(50, $handler->getPriority());
    }

    public function testAbstractHandlerSkipParentResult()
    {
        $handler = new class extends AbstractHandler {
            protected bool $skipParentResult = true;

            public function supports(string $magicMethod): bool
            {
                return $magicMethod === '__get';
            }

            public function handle(object $instance, array $arguments)
            {
                return $this->continue();
            }
        };

        $this->assertTrue($handler->skipParentResult());
    }

    public function testContinueMarker()
    {
        $handler = new class extends AbstractHandler {
            public function supports(string $magicMethod): bool
            {
                return $magicMethod === '__get';
            }

            public function handle(object $instance, array $arguments)
            {
                return $this->continue();
            }
        };

        $result = $handler->handle((object)[], ['test']);
        $this->assertEquals(AbstractHandler::MAGIC_METHOD_CONTINUE, $result);
    }
}
