<?php

namespace Tests\Support;

use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Exception\InternalServerErrorException;

class FillTest extends TestCase
{
    /**
     * @throws InternalServerErrorException
     * @throws \ReflectionException
     */
    public function testAutoFill()
    {
        $params = [
            'eyes'  => [['size' => 'big']],
            'speak' => [
                'language' => 'english',
            ],
            'hair'  => [
                'short',
                'long',
            ],
        ];

        $cat = new Cat($params);
        $this->assertEquals('big', $cat->getEyes()[0]->getSize());
        $this->assertNull($cat->getEat());
        $this->assertEquals('english', $cat->getSpeak()->getLanguage());
        $this->assertIsArray($cat->getHair());
        $this->assertArrayHasKey('eyes', $cat->toArray());
        $this->assertArrayHasKey('speak', $cat->toCamelCaseArray());
        $this->assertArrayHasKey('hair', $cat->toSnakeCaseArray());
    }
}
