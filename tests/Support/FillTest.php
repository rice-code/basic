<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Tests\Support\Annotation\Cat;

class FillTest extends TestCase {
    public function testAutoFill() {
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
        $this->assertEquals('big eyes', $cat->eyes);
        $this->assertNull($cat->eat);
        $this->assertEquals('english', $cat->speak->language);
        $this->assertIsArray($cat->hair);
        $this->assertArrayHasKey('eyes', $cat->toArray());
        $this->assertArrayHasKey('speak', $cat->toCamelCaseArray());
        $this->assertArrayHasKey('hair', $cat->toSnakeCaseArray());
    }
}
