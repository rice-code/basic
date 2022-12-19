<?php

namespace Tests\Support\Converts;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Converts\LengthConvert;

class LengthConvertTest extends TestCase
{
    public function testLengthConvert(): void
    {
        $lengthConvert = new LengthConvert('1', LengthConvert::METRIC_CM);
        $this->assertEquals('0.01', $lengthConvert->getNum(2));

        $lengthConvert = new LengthConvert('1', LengthConvert::METRIC_KM);
        $this->assertEquals('1000', $lengthConvert->getNum());

        $this->assertEquals('1000', $lengthConvert->to(LengthConvert::METRIC_M, 0));

        $this->assertEquals('25.4000', $lengthConvert->to(LengthConvert::BRITISH_IN));
    }
}
