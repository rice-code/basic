<?php

namespace Rice\Basic\Components\Enum\ReturnCode;

use Rice\Basic\Components\Enum\BaseEnum;

class ReturnCodeEnum extends BaseEnum implements ClientErrorCode, SystemErrorCode, ServiceErrorCode
{
    /**
     * @default OK
     */
    public const OK = '00000';
}
