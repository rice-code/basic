<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\components\Enum\LangEnum;

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
