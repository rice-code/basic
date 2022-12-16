<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Enum\ExceptionEnum;

class EnumTest extends TestCase
{
    public function testGetConstants(): void
    {
        $this->assertIsArray(ExceptionEnum::getChildConstants());
        $arr = [ExceptionEnum::ATTR_NOT_DEFINE, ExceptionEnum::METHOD_NOT_DEFINE];

        $this->assertEquals($arr, [
            ExceptionEnum::getParentConstants()['ATTR_NOT_DEFINE'],
            ExceptionEnum::getParentConstants()['METHOD_NOT_DEFINE'],
        ]);

        $this->assertEquals($arr, [
            ExceptionEnum::getConstants()['ATTR_NOT_DEFINE'],
            ExceptionEnum::getConstants()['METHOD_NOT_DEFINE'],
        ]);
    }
}
