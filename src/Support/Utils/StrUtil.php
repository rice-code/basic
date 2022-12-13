<?php

namespace Rice\Basic\Support\Utils;

use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Exception\SupportException;

class StrUtil
{
    /**
     * 大写字母判断.
     *
     * @param string $str
     * @return bool
     */
    public static function isUpper(string $str): bool
    {
        if (preg_match('/^[A-Z]+$/', $str)) {
            return true;
        }

        return false;
    }

    /**
     * 小写字母判断.
     *
     * @param string $str
     * @return bool
     */
    public static function isLower(string $str)
    {
        if (preg_match('/^[a-z]+$/', $str)) {
            return true;
        }

        return false;
    }

    /**
     * 驼峰转蛇形.
     *
     * @param string $name
     * @return string
     * @throws SupportException
     */
    public static function camelCaseToSnakeCase(string $name): string
    {
        $len = strlen($name);

        if (0 === $len) {
            throw new SupportException(BaseEnum::INVALID_PARAM);
        }

        $newName = $name[0];
        for ($i = 1; $i < $len; ++$i) {
            $char = $name[$i];
            if (self::isUpper($char)) {
                $newName .= '_' . strtolower($char);

                continue;
            }

            $newName .= $char;
        }

        return $newName;
    }

    public static function snakeCaseToCamelCase(string $name)
    {
        $newName = '';
        $wordArr = explode('_', $name);
        foreach ($wordArr as $word) {
            if ('' == $newName) {
                $newName .= $word;

                continue;
            }
            $newName .= ucfirst($word);
        }

        return $newName;
    }
}
