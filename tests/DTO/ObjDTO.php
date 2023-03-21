<?php

namespace Tests\DTO;

use Rice\Basic\Components\DTO\BaseDTO;

/**
 * Class ObjDTOBase.
 * @method self setName(string $value)
 * @method string getName() 获取名称
 */
class ObjDTO extends BaseDTO
{
    protected $name;
}
