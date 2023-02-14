<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Enum\SupportEnum;
use Rice\Basic\Exception\SupportException;

class ExceptionTest extends TestCase
{
    public function testI18n(): void
    {
        try {
            throw new SupportException(SupportEnum::METHOD_NOT_DEFINE);
        } catch (SupportException $e) {
            $this->assertEquals('属性未定义', $e->getMessage());
        }
    }
}