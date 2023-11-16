<?php

namespace Tests\DTO;

use Rice\Basic\Components\DTO\PageDTO;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * Class ObjDTOBase.
 * @method self setShopId(int $shopId)
 * @method string getShopId() 获取店铺id
 */
class OrderListDTO extends PageDTO
{
    use AutoFillProperties;
    /**
     * 店铺id
     *
     * @var int $shopId
     */
    protected $shopId;
}