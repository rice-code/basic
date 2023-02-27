<?php

namespace Rice\Basic\components\Enum\ReturnCode;

use Rice\Basic\components\Enum\BaseEnum;

class ReturnCodeEnum extends BaseEnum
    implements ClientErrorCode, SystemErrorCode, ServiceErrorCode
{
    /**
     * @default OK
     */
    public const OK = '00000';
}
