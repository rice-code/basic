<?php

namespace Test\Support\Converts;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Converts\WeightConvert;

class WeightConvertTest extends TestCase
{
    public function testLengthConvert(): void
    {
        $weightConvert = new WeightConvert('1', WeightConvert::METRIC_G);
        $this->assertEquals('0.001', $weightConvert->getNum(3));

        $weightConvert = new WeightConvert('1', WeightConvert::METRIC_KG);
        $this->assertEquals('1', $weightConvert->getNum());

        $this->assertEquals('6.3', $weightConvert->to(WeightConvert::BRITISH_ST, 1));

        $this->assertEquals('0.0283', $weightConvert->to(WeightConvert::BRITISH_OZ));
    }
}
