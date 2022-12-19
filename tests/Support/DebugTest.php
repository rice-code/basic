<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;

class DebugTest extends TestCase
{
    public function testShowTakeTime()
    {
        $debug = new \Rice\Basic\Support\Debug();
        $this->assertIsString($debug->setTakeTime('start', microtime(true))->setTakeTime('end', microtime(true))->showTakeTime());
    }
}
