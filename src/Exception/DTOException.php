<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\LangEnum;

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
