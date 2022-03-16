<?php

namespace Rice\Basic\Enum;


class CodeEnum extends BaseEnum
{
    // 语言包名称
    public const LANG_NAME = 'code';
    // 成功
    public const SUCCESS = 0;
    // 参数非法
    public const INVALID_PARAM = 100001;
    // 请求非法
    public const INVALID_REQUEST = 100002;
    // 异常中断
    public const ABNORMAL_INTERRUPT = 100003;

}