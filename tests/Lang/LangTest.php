<?php

namespace Lang;

use Rice\Basic\Support\Lang;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\BaseEnum;

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
