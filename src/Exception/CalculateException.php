<?php

namespace Rice\Basic\Exception;

class CalculateException extends BaseException {
    public const CANNOT_DIVIDE_BY_ZERO = 'cannot_divide_by_zero';

    public static function getLangName(): string {
        return 'calculate';
    }
}
