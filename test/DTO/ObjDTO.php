<?php

namespace Test\DTO;

use Rice\Basic\DTO\BaseDTO;

/**
 * Class ObjDTOBase.
 * @method self setName(string $value)
 * @method string getName() 获取名称
 */
class ObjDTO extends BaseDTO
{
    protected $name;
}
