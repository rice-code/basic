<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\DataExtract;

class DataExtractTest extends TestCase
{
    public function testGet(): void
    {
        $json = '{"data": [{"number": 1}]}';
        $this->assertEquals(1, DataExtract::get($json, 'data.0.number'));

        $array = ['data' => [['number' => 1]]];
        $this->assertEquals(1, DataExtract::get($array, 'data.0.number'));

        $object = json_decode($json, false);
        $this->assertEquals(1, DataExtract::get($object, 'data.0.number'));
    }
}
