<?php

namespace Rice\Basic\Support;

/**
 * 数据提取
 * Class DataExtract
 */
class DataExtract
{
    /**
     * @var bool 是否驼峰
     */
    public static $camelCase = false;

    /**
     * @var bool 是否蛇形
     */
    public static $snakeCase = false;

    /**
     * 数据提取
     * @param string|array|object $source 数据源
     * @param string $key 提取key
     * @param null $default 默认值
     * @return mixed|null
     */
    public static function get($source, $key, $default = null)
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
                $name = snake_case_to_camel_case($name);
            }

            if (self::$snakeCase) {
                $name = camel_case_to_snake_case($name);
            }

            if (!isset($source[$name])) {
                return $default;
            }
            $source = $source[$name];
        }
        return $source;
    }

    public static function getCamelCase($source, $key, $default = null)
    {
        self::$camelCase = true;
        $val = self::get($source, $key, $default);
        self::$camelCase = false;

        return $val;
    }

    public static function getSnakeCase($source, $key, $default = null)
    {
        self::$snakeCase = true;
        $val = self::get($source, $key, $default);
        self::$snakeCase = false;

        return $val;
    }
}