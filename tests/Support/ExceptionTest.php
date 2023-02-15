<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Enum\SupportEnum;
use Rice\Basic\Exception\SupportException;
use Rice\Basic\Support\Lang;

class ExceptionTest extends TestCase
{
    public function testI18n(): void
    {
        try {
            Lang::getInstance()->setLocale('en');
            throw new SupportException(SupportEnum::METHOD_NOT_DEFINE);
        } catch (SupportException $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }
    }
}