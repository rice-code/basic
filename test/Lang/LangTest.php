<?php

namespace Lang;

use Rice\Basic\Support\Lang;
use Rice\Basic\Enum\BaseEnum;
use PHPUnit\Framework\TestCase;

class LangTest extends TestCase
{
    public function testLang(): void
    {
        $this->assertArrayHasKey(
            BaseEnum::STRING_IS_EMPTY,
            Lang::getInstance()
                ->setLocale('en')
                ->loadFile()
        );
    }
}
