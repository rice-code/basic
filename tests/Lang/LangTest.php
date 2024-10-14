<?php

namespace Lang;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\SupportEnum;
use Rice\Basic\Components\Exception\InternalServerErrorException;

class LangTest extends TestCase
{
    public function testLang(): void
    {
        try {
            throw new InternalServerErrorException(SupportEnum::CANNOT_DIVIDE_BY_ZERO);
        } catch (\Throwable $throwable) {
            $this->assertEquals('不能除以零', $throwable->getMessage());
        }
    }
}
