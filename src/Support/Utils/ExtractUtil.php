<?php

namespace Rice\Basic\Support\Utils;

use Rice\Basic\Components\Exception\InternalServerErrorException;

/**
 * 数据提取
 * Class ExtractUtil.
 */
class ExtractUtil
{
    /**
     * @var bool 是否驼峰
     */
    public static bool $camelCase = false;

    /**
     * @var bool 是否蛇形
     */
    public static bool $snakeCase = false;

    /**
     * 数据提取.
     * @param string|array|object $source  数据源
     * @param string              $key     提取key
     * @param null                $default 默认值
     * @return mixed|null
     * @throws InternalServerErrorException
     */
    public static function get($source, string $key, $default = null)
    {
        if (is_string($source)) {
            $source = json_decode($source, true);
        }

        if (is_object($source)) {
            $source = json_decode(json_encode($source, JSON_UNESCAPED_UNICODE), true);
        }

        $keys = explode('.', $key);
        foreach ($keys as $name) {
            if (self::$camelCase) {
                $name = StrUtil::snakeCaseToCamelCase($name);
            }

            if (self::$snakeCase) {
                $name = StrUtil::camelCaseToSnakeCase($name);
            }

            if (!isset($source[$name])) {
                return $default;
            }
            $source = $source[$name];
        }

        return $source;
    }

    /**
     * @throws InternalServerErrorException
     */
    public static function getCamelCase($source, $key, $default = null)
    {
        self::$camelCase = true;
        $val             = self::get($source, $key, $default);
        self::$camelCase = false;

        return $val;
    }

    /**
     * @throws InternalServerErrorException
     */
    public static function getSnakeCase($source, $key, $default = null)
    {
        self::$snakeCase = true;
        $val             = self::get($source, $key, $default);
        self::$snakeCase = false;

        return $val;
    }

    /**
     * 按照 原键值 -> 驼峰 -> 蛇形 依次获取值
     * @throws InternalServerErrorException
     */
    public static function getValue($params, $key)
    {
        $value = self::get($params, $key);

        if (is_null($value)) {
            $value = self::getCamelCase($params, $key);
        }

        if (is_null($value)) {
            $value = self::getSnakeCase($params, $key);
        }

        return $value;
    }
}
