<?php

namespace Tests\DTO;

use PHPUnit\Framework\TestCase;


class DTOTest extends TestCase
{
    public function testSetElement(): void
    {
        $dto = new ObjDTO();
        $dto->setName('tests');
        $this->assertEquals('tests', $dto->getName());
        $dto->toArray();
    }
}