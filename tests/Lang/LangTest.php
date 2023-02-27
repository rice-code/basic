<?php

namespace Lang;

use PHPUnit\Framework\TestCase;
use Rice\Basic\components\Enum\BaseEnum;
use Rice\Basic\Support\Lang;

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
