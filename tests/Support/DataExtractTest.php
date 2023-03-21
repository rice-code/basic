<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Utils\ExtractUtil;
use Rice\Basic\Components\Exception\SupportException;

class DataExtractTest extends TestCase
{
    /**
     * @throws SupportException
     */
    public function testGet(): void
    {
        $json = '{"data": [{"myNumber": 1}]}';
        $this->assertEquals(1, ExtractUtil::get($json, 'data.0.myNumber'));

        $array = ['data' => [['myNumber' => 1]]];
        $this->assertEquals(1, ExtractUtil::get($array, 'data.0.myNumber'));

        $object = json_decode($json, false);
        $this->assertEquals(1, ExtractUtil::get($object, 'data.0.myNumber'));

        // 蛇形转驼峰
        $this->assertEquals(1, ExtractUtil::getCamelCase($array, 'data.0.my_number'));
        $this->assertEquals(1, ExtractUtil::getValue($array, 'data.0.my_number'));

        $array = ['data' => [['my_number' => 1]]];
        // 驼峰转蛇形
        $this->assertEquals(1, ExtractUtil::getSnakeCase($array, 'data.0.myNumber'));
        $this->assertEquals(1, ExtractUtil::getValue($array, 'data.0.myNumber'));
    }
}
