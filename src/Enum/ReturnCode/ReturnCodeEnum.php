<?php

namespace Rice\Basic\Enum\ReturnCode;

use Rice\Basic\Enum\BaseEnum;

class ReturnCodeEnum extends BaseEnum
    implements ClientErrorCode, SystemErrorCode, ServiceErrorCode
{
    /**
     * @default OK
     */
    public const OK = '00000';
}
