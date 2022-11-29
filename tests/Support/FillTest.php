<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Tests\Support\Annotation\Cat;

class FillTest extends TestCase
{
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
        dd();
        $this->assertEquals('english', $cat->getSpeak()->getLanguage());
        $this->assertIsArray($cat->getHair());
        $this->assertArrayHasKey('eyes', $cat->toArray());
        $this->assertArrayHasKey('speak', $cat->toCamelCaseArray());
        $this->assertArrayHasKey('hair', $cat->toSnakeCaseArray());
    }
}
