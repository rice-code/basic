<?php

namespace Test\DTO;

use PHPUnit\Framework\TestCase;

class DTOTest extends TestCase
{
    public function testSetElement(): void
    {
        $dto = new ObjDTO();
        $dto->setName('test');
        $this->assertEquals('test', $dto->getName());
        $dto->toArray();
    }
}
