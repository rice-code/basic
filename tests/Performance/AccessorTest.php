<?php

namespace Tests\Performance;

use Tests\DTO\OrderListDTO;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Utils\PerfUtil;

class AccessorTest extends TestCase
{
    public function test100(): void
    {
        $microseconds =  PerfUtil::microseconds(100, function () {
            new OrderListDTO([
                'id'       => 1,
                'order_no' => 'abc',
                'shop_id'  => 1,
                'price'    => '100.0',
                'num'      => 100,
                'page'     => 3,
                'per_page' => 4,
            ]);
        });
        $actual = $microseconds / (1000 * 1000 * 1.0);
        var_dump('accessor test100: '.$actual.'s');
        $this->assertLessThanOrEqual(1, $actual);
    }

    public function test1000(): void
    {
        $microseconds =  PerfUtil::microseconds(1000, function () {
            new OrderListDTO([
                'id'       => 1,
                'order_no' => 'abc',
                'shop_id'  => 1,
                'price'    => '100.0',
                'num'      => 100,
                'page'     => 3,
                'per_page' => 4,
            ]);
        });
        $actual = $microseconds / (1000 * 1000 * 1.0);
        var_dump('accessor test1000: '.$actual.'s');
        $this->assertLessThanOrEqual(1, $actual);
    }

    public function test10000(): void
    {
        $microseconds =  PerfUtil::microseconds(10000, function () {
            new OrderListDTO([
                'id'       => 1,
                'order_no' => 'abc',
                'shop_id'  => 1,
                'price'    => '100.0',
                'num'      => 100,
                'page'     => 3,
                'per_page' => 4,
            ]);
        });
        $actual = $microseconds / (1000 * 1000 * 1.0);
        var_dump('accessor test10000: '.$actual.'s');
        $this->assertLessThanOrEqual(3, $actual);
    }
}
