<?php


namespace common;


use PHPUnit\Framework\TestCase;

class CommonTest extends TestCase
{
    public function testIsUpper(): void
    {
        $this->assertEquals(true, is_upper('A'));
        $this->assertEquals(false, is_upper('a'));
    }

    public function testIsLower(): void
    {
        $this->assertEquals(true, is_lower('a'));
        $this->assertEquals(false, is_lower('A'));
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