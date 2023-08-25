<?php

namespace Tests\Support;

use Rice\Basic\Support\Lang;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\BizEnum;
use Rice\Basic\Components\Enum\SupportEnum;
use Rice\Basic\Components\Exception\BizException;
use Rice\Basic\Components\Exception\SupportException;

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

        try {
            Lang::getInstance()->setLocale('en');

            throw new BizException(BizEnum::DEFAULT);
        } catch (BizException $e) {
            $this->assertEquals('Business Error', $e->getMessage());
        }
    }
}
