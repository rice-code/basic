<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\BizEnum;

class BizException extends BaseException
{
    public static function enumClass(): string
    {
        return BizEnum::class;
    }
}