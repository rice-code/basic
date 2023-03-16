<?php

namespace Tests\Support;

use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\components\Exception\SupportException;

class FillTest extends TestCase
{
    /**
     * @throws SupportException
     * @throws \ReflectionException
     */
    public function testAutoFill()
    {
        $params = [
            'eyes'  => 'big eyes',
            'speak' => [
                'language' => 'english',
            ],
            'hair'  => [
                'short',
                'long',
            ],
        ];

        $cat = new Cat($params);
        $this->assertEquals('big eyes', $cat->getEyes());
        $this->assertNull($cat->getEat());
        $this->assertEquals('english', $cat->getSpeak()->getLanguage());
        $this->assertIsArray($cat->getHair());
        $this->assertArrayHasKey('eyes', $cat->toArray());
        $this->assertArrayHasKey('speak', $cat->toCamelCaseArray());
        $this->assertArrayHasKey('hair', $cat->toSnakeCaseArray());
    }
}
