<?php


namespace Lang;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Lang;

class LangTest extends TestCase
{
    public function testLang(): void
    {
        $this->assertEquals(
            'string is empty',
            Lang::getInstance()
                ->setLocale('en')
                ->setKey(BaseEnum::STRING_IS_EMPTY)->getMessage()
        );

        $this->assertEquals(
            '字符串为空',
            Lang::getInstance()
                ->setLocale('zh-CN')
                ->setKey(BaseEnum::STRING_IS_EMPTY)->getMessage()
        );
    }
}