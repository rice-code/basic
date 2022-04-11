<?php

namespace Tests\DTO;


use Rice\Basic\Dto\DTOBase;

/**
 * @method getName() 获取名称
 * @method setName(string $string)
 * Class ObjDTOBase
 * @package test\DTOBase
 */
class ObjDTO extends DTOBase
{
    public $name;
}