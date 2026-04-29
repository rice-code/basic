<?php

namespace Rice\Basic\Infrastructure\Enum\ReturnCode;

use Rice\Basic\Infrastructure\Enum\BaseEnum;

class ReturnCodeEnum extends BaseEnum implements ClientErrorCode, SystemErrorCode, ServiceErrorCode
{
    public const OK = '00000';
}