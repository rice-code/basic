<?php

namespace Rice\Basic\Components\Enum;

class NameTypeEnum extends BaseEnum
{
    // 无限制
    public const UNLIMITED = 0;
    // 驼峰
    public const CAMEL_CASE = 1;
    // 蛇形
    public const SNAKE_CASE = 2;
}
