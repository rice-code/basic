<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

class AssemblerException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::ASSEMBLER;
    }
}
