<?php

namespace Tests\DTO;

use Rice\Basic\Components\DTO\PageDTO;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * Class ObjDTOBase.
 * @method self setId(int $id)
 * @method int getId() 获取订单id
 * @method self setOrderNo(string $orderNo)
 * @method string getOrderNo() 获取订单号
 * @method self setShopId(int $shopId)
 * @method string getShopId() 获取店铺id
 * @method self setPrice(string $price)
 * @method string getPrice() 获取价格
 * @method self setNum(int $num)
 * @method string getNum() 获取数量
 */
class OrderListDTO extends PageDTO
{
    use AutoFillProperties;

    /**
     * 订单id.
     *
     * @var int
     */
    protected int $id;
    /**
     * 店铺id.
     *
     * @var int
     */
    protected int $shopId;
    /**
     * 外部展示订单号.
     *
     * @var string
     */
    protected string $orderNo;
    /**
     * 价格
     *
     * @var string
     */
    protected string $price;
    /**
     * 数量.
     *
     * @var string
     */
    protected string $num;
}
