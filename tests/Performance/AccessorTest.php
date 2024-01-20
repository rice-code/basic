<?php

namespace Tests\Performance;

use Tests\Support\Entity\Perf;
use PHPUnit\Framework\TestCase;

class AccessorTest extends TestCase
{
    public function test100(): void
    {
        echo '| 功能                |  次数   |      耗时(秒) |' . PHP_EOL;
        echo '|:------------------|:-----:|-----------:|' . PHP_EOL;
        Perf::runAccessor(100);
        Perf::runAutoFill(100);
        $this->assertTrue(true);
    }

    public function test1000(): void
    {
        Perf::runAccessor(1000);
        Perf::runAutoFill(1000);
        $this->assertTrue(true);
    }

    public function test10000(): void
    {
        Perf::runAccessor(10000);
        Perf::runAutoFill(10000);
        $this->assertTrue(true);
    }
}
