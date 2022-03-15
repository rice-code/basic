<?php


namespace lang;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Lang;

class LangTest extends TestCase
{
    public function testLang(): void
    {
        $this->assertEquals(
            'string is empty',
            (new Lang())->setLocale('en')
                ->setKey(BaseEnum::STRING_IS_EMPTY)->getMessage()
        );

        $this->assertEquals(
            '字符串为空',
            (new Lang())->setLocale('zh-CN')
                ->setKey(BaseEnum::STRING_IS_EMPTY)->getMessage()
        );
    }
}