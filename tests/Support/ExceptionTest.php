<?php

namespace Tests\Support;

use Rice\Basic\Support\Lang;
use PHPUnit\Framework\TestCase;
use Rice\Basic\components\Enum\SupportEnum;
use Rice\Basic\components\Exception\SupportException;

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
