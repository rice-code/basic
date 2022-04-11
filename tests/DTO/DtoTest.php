<?php

namespace Tests\DTO;

use PHPUnit\Framework\TestCase;


class DtoTest extends TestCase
{
    public function testSetElement()
    {
        $dto = new ObjDTO();
        $dto->setName('tests');
        $this->assertEquals('tests', $dto->getName());
        $dto->toArray();
    }
}