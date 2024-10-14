<?php

namespace Tests\DTO;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Exception\InternalServerErrorException;

class DTOTest extends TestCase
{
    /**
     * @throws InternalServerErrorException
     */
    public function testSetElement(): void
    {
        $dto = new ObjDTO();
        $dto->setName('tests');
        $this->assertEquals('tests', $dto->getName());
        $dto->toArray();
    }

    public function testPageDTO(): void
    {
        $dto = new OrderListDTO(['shop_id' => 1, 'page' => 3, 'per_page' => 10]);
        $this->assertEquals(1, $dto->getShopId());
        $this->assertEquals(3, $dto->getPage());
        $this->assertEquals(10, $dto->getPerPage());
    }
}
