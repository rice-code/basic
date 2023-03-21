<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\LangEnum;

/**
 * 数据传输对象异常
 * Class DTOException.
 */
class DTOException extends BaseException
{
    public static function getLangName(): string
    {
        return LangEnum::DTO;
    }
}
