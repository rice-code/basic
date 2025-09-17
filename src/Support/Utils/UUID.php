<?php

namespace Rice\Basic\Support\Utils;

/**
 * UUID生成工具类
 * 提供不依赖外部库的UUID v4生成功能.
 */
class UUID
{
    /**
     * 生成UUID v4格式的唯一标识符
     * 实现了RFC 4122规范的UUID v4版本.
     *
     * @return string UUID v4格式的字符串
     */
    public static function v4(): string
    {
        // 生成随机字节
        $data = random_bytes(16);

        // 根据RFC 4122设置版本和变体位
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40); // 设置版本为4
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80); // 设置变体为DCE 1.1

        // 格式化UUID字符串
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * 检查字符串是否为有效的UUID格式.
     *
     * @param string $uuid 要检查的字符串
     * @return bool 是否为有效的UUID
     */
    public static function isValid(string $uuid): bool
    {
        return 1 === preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    /**
     * 检查字符串是否为有效的UUID v4格式.
     *
     * @param string $uuid 要检查的字符串
     * @return bool 是否为有效的UUID v4
     */
    public static function isValidV4(string $uuid): bool
    {
        return 1 === preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }

    /**
     * 生成紧凑格式的UUID（不含连字符）.
     *
     * @return string 紧凑格式的UUID
     */
    public static function compact(): string
    {
        return str_replace('-', '', self::v4());
    }

    /**
     * 生成基于时间的UUID（简化版本）
     * 不严格遵循RFC规范，但在需要排序的场景下有用.
     *
     * @return string 基于时间的UUID
     */
    public static function timeBased(): string
    {
        // 获取当前时间戳（毫秒）
        $timestamp = str_pad((string) (int) (microtime(true) * 1000), 13, '0', STR_PAD_LEFT);
        // 添加随机字符串
        $random = bin2hex(random_bytes(10));

        // 组合成时间相关的唯一ID
        return substr($timestamp . $random, 0, 32);
    }
}
