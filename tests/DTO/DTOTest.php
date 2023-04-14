<?php

namespace Tests\DTO;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Exception\SupportException;

class DTOTest extends TestCase
{
    /**
     * @throws SupportException
     */
    public function testSetElement(): void
    {
        $dto = new ObjDTO();
        $dto->setName('tests');
        $this->assertEquals('tests', $dto->getName());
        $dto->toArray();
    }
}
