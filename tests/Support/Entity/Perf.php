<?php

namespace Tests\Support\Entity;

use Tests\DTO\OrderListDTO;
use Rice\Basic\Support\Utils\PerfUtil;
use Rice\Basic\Components\Entity\BaseEntity;

class Perf extends BaseEntity
{
    public static function run(int $loop): void
    {
        $microseconds = PerfUtil::microseconds($loop, function () {
            $dto = new OrderListDTO([]);
            $dto->setId(1);
            $dto->getId();
            $dto->setShopId(1);
            $dto->getShopId();
            $dto->setOrderNo('aaaaa');
            $dto->getOrderNo();
            $dto->setNum(1);
            $dto->getNum();
            $dto->setPrice('11111');
            $dto->getPrice();
            $dto->setPage(1);
            $dto->getPage();
            $dto->setPerPage(11);
            $dto->getPerPage();
        });
        $actual = $microseconds / (1000 * 1000 * 1.0);
        var_dump('accessor test' . $loop . ': ' . $actual . 's');

        $microseconds = PerfUtil::microseconds($loop, function () {
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
        var_dump('auto fill test' . $loop . ': ' . $actual . 's');
    }
}
