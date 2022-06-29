<?php

namespace Rice\Basic\Support;

/**
 * 数据提取
 * Class DataExtract
 */
class DataExtract
{
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
            if (!isset($source[$name])) {
                return $default;
            }
            $source = $source[$name];
        }
        return $source;
    }
}