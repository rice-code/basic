<?php

namespace Rice\Basic\Tests\Support\Utils;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Utils\ArrUtil;

class ArrUtilTest extends TestCase
{
    public function testWrapWithString()
    {
        $result = ArrUtil::wrap('test');

        $this->assertIsArray($result);
        $this->assertEquals(['test'], $result);
    }

    public function testWrapWithArray()
    {
        $result = ArrUtil::wrap(['a', 'b', 'c']);

        $this->assertIsArray($result);
        $this->assertEquals(['a', 'b', 'c'], $result);
    }

    public function testWrapWithEmptyString()
    {
        $result = ArrUtil::wrap('');

        $this->assertIsArray($result);
        $this->assertEquals([''], $result);
    }
}
