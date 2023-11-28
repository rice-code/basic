<?php

namespace Tests\Performance;

use Tests\Support\Entity\Perf;
use PHPUnit\Framework\TestCase;

class AccessorTest extends TestCase
{
    public function testPerf(): void
    {
        Perf::run(100);
        Perf::run(1000);
        Perf::run(10000);
        $this->assertTrue(true);
    }
}
