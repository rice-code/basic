<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Components\Enum\BizEnum;

class BizException extends BaseException
{
    public static function enumClass(): string
    {
        return BizEnum::class;
    }

    /**
     * @throws BizException
     */
    public static function default(): void
    {
        throw new self(BizEnum::DEFAULT);
    }
}
