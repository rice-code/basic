<?php

namespace Rice\Basic\Support\Utils;

use Rice\Basic\Components\Enum\TypeEnum;
use Rice\Basic\Components\Exception\InternalServerErrorException;

class VerifyUtil
{
    /**
     * 是否开启强类型校验.
     * @var bool
     */
    public static bool $strongTypeIsEnable = true;

    public static function notNull($obj): bool
    {
        return !is_null($obj);
    }

    public static function notEmpty($obj): bool
    {
        return !empty($obj);
    }

    public static function notNullAndNotEmpty($obj): bool
    {
        return self::notNull($obj) && self::notEmpty($obj);
    }

    /**
     * 只校验已知类型.
     * @param $type
     * @param $value
     * @return bool
     */
    public static function strongType($type, $value): bool
    {
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
     * @throws InternalServerErrorException
     */
    public static function throwStrongType($type, $value): void
    {
        if (self::$strongTypeIsEnable && !self::strongType($type, $value)) {
            throw new InternalServerErrorException(TypeEnum::INVALID_TYPE);
        }
    }
}
