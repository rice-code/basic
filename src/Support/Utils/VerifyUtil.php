<?php

namespace Rice\Basic\Support\Utils;

use Rice\Basic\Infrastructure\Enum\TypeEnum;
use Rice\Basic\Infrastructure\Exception\InternalServerErrorException;

class VerifyUtil
{
    /**
     * 是否开启强类型校验.
     * @var bool
     */
    public static bool $strongTypeIsEnable = true;

    /**
     * @param mixed $obj
     */
    public static function notNull($obj): bool
    {
        return !is_null($obj);
    }

    /**
     * @param mixed $obj
     */
    public static function notEmpty($obj): bool
    {
        return !empty($obj);
    }

    /**
     * @param mixed $obj
     */
    public static function notNullAndNotEmpty($obj): bool
    {
        return self::notNull($obj) && self::notEmpty($obj);
    }

    /**
     * 只校验已知类型.
     * @param string $type
     * @param mixed $value
     * @return bool
     */
    public static function strongType(string $type, $value): bool
    {
        switch ($type) {
            case 'string':
                return is_string($value);
            case 'int':
            case 'integer':
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
     * @param string $type
     * @param mixed $value
     * @throws InternalServerErrorException
     */
    public static function throwStrongType(string $type, $value): void
    {
        if (self::$strongTypeIsEnable && !self::strongType($type, $value)) {
            throw new InternalServerErrorException(TypeEnum::INVALID_TYPE);
        }
    }
}
