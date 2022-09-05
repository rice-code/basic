<?php

namespace Tests\DTO;


use Rice\Basic\Dto\BaseDTO;

/**
 * @method getName() 获取名称
 * @method setName(string $string)
 * Class ObjDTOBase
 * @package test\DTOBase
 */
class ObjDTO extends BaseDTO
{
    public $name;
}