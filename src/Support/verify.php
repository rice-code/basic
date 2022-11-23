<?php

namespace Rice\Basic\Support;

use Rice\Basic\Exception\TypeException;

class verify {
    /**
     * 是否开启强类型校验.
     * @var bool
     */
    public static $strongTypeIsEnable = true;

    /**
     * 只校验已知类型.
     * @param $type
     * @param $value
     * @return bool
     */
    public static function strongType($type, $value): bool {
        switch ($type) {
            case 'string':
                return is_string($value);
            case 'int' | 'integer':
                return is_int($value);
            case 'bool':
                return is_bool($value);
            case 'array':
                return is_array($value);
            default:
                return true;
        }
    }

    /**
     * 强类型异常抛出.
     * @param $type
     * @param $value
     * @throws TypeException
     */
    public static function throwStrongType($type, $value): void {
        if (self::$strongTypeIsEnable && !self::strongType($type, $value)) {
            throw new TypeException(TypeException::INVALID_TYPE);
        }
    }
}
