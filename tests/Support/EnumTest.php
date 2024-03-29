<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\SupportEnum;
use Rice\Basic\Components\Enum\ExceptionEnum;

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

        $this->assertEquals(
            SupportEnum::CANNOT_DIVIDE_BY_ZERO,
            SupportEnum::getConstants()['CANNOT_DIVIDE_BY_ZERO']
        );
    }
}
