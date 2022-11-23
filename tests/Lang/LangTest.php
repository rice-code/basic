<?php

namespace Lang;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Exception\CommonException;
use Rice\Basic\Support\Lang;

class LangTest extends TestCase
{
    public function testLang(): void
    {
        $this->assertEquals(
            'string is empty',
            Lang::getInstance()
                ->setLocale('en')
                ->loadFile()
                ->getMessage(CommonException::STRING_IS_EMPTY)
        );

        $this->assertEquals(
            '字符串为空',
            Lang::getInstance()
                ->setLocale('zh-CN')
                ->loadFile()
                ->getMessage(CommonException::STRING_IS_EMPTY)
        );
    }
}
