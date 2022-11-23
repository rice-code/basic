<?php

namespace Rice\Basic\Exception;

class CodeException extends BaseException {
    // 成功
    public const SUCCESS = 0;
    // 参数非法
    public const INVALID_PARAM = 100001;
    // 请求非法
    public const INVALID_REQUEST = 100002;
    // 异常中断
    public const ABNORMAL_INTERRUPT = 100003;

    public static function getLangName(): string {
        return 'code';
    }
}
