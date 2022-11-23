<?php

namespace Common;

use PHPUnit\Framework\TestCase;

class CommonTest extends TestCase
{
    public function testIsUpper(): void
    {
        $this->assertTrue(is_upper('A'));
        $this->assertFalse(is_upper('a'));
    }

    public function testIsLower(): void
    {
        $this->assertTrue(is_lower('a'));
        $this->assertFalse(is_lower('A'));
    }

    public function testCamelCaseToSnakeCase(): void
    {
        $this->assertEquals('a_bc_d', camel_case_to_snake_case('aBcD'));
    }

    public function testSnakeCaseToCamelCase(): void
    {
        $this->assertEquals('aBCDe', snake_case_to_camel_case('a_b_C_De'));
    }
}
