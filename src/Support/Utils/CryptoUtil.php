<?php

namespace Rice\Basic\Support\Utils;

use Rice\Basic\Infrastructure\Exception\InternalServerErrorException;

/**
 * 加密工具类
 * 为敏感数据提供加密和解密功能，增强数据安全性.
 */
class CryptoUtil
{
    /**
     * @var string 默认加密算法
     */
    private const DEFAULT_ALGORITHM = 'AES-256-CBC';

    /**
     * @var int 默认密钥长度
     */
    private const DEFAULT_KEY_LENGTH = 32; // 256位

    /**
     * @var string 默认哈希算法
     */
    private const DEFAULT_HASH_ALGORITHM = 'sha256';

    /**
     * 加密数据.
     *
     * @param string $data    要加密的数据
     * @param string $key     密钥
     * @param array  $options 加密选项
     * @return string 加密后的字符串
     * @throws EncryptionException
     */
    public static function encrypt(string $data, string $key, array $options = []): string
    {
        try {
            $algorithm     = $options['algorithm']      ?? self::DEFAULT_ALGORITHM;
            $hashAlgorithm = $options['hash_algorithm'] ?? self::DEFAULT_HASH_ALGORITHM;

            // 确保密钥长度符合算法要求
            $key = self::prepareKey($key, $hashAlgorithm);

            // 生成初始化向量
            $ivLength = openssl_cipher_iv_length($algorithm);
            $iv       = openssl_random_pseudo_bytes($ivLength);

            // 加密数据
            $encrypted = openssl_encrypt($data, $algorithm, $key, OPENSSL_RAW_DATA, $iv);

            if (false === $encrypted) {
                throw new EncryptionException('加密失败: ' . openssl_error_string());
            }

            // 组合IV和加密数据并进行Base64编码
            $result = base64_encode($iv . $encrypted);

            return $result;
        } catch (\Exception $e) {
            throw new EncryptionException('加密过程中发生错误: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 解密数据.
     *
     * @param string $data    要解密的数据
     * @param string $key     密钥
     * @param array  $options 解密选项
     * @return string 解密后的字符串
     * @throws EncryptionException
     */
    public static function decrypt(string $data, string $key, array $options = []): string
    {
        try {
            $algorithm     = $options['algorithm']      ?? self::DEFAULT_ALGORITHM;
            $hashAlgorithm = $options['hash_algorithm'] ?? self::DEFAULT_HASH_ALGORITHM;

            // 确保密钥长度符合算法要求
            $key = self::prepareKey($key, $hashAlgorithm);

            // Base64解码
            $decoded = base64_decode($data, true);

            if (false === $decoded) {
                throw new EncryptionException('数据格式无效，无法进行Base64解码');
            }

            // 提取IV
            $ivLength      = openssl_cipher_iv_length($algorithm);
            $iv            = substr($decoded, 0, $ivLength);
            $encryptedData = substr($decoded, $ivLength);

            // 解密数据
            $decrypted = openssl_decrypt($encryptedData, $algorithm, $key, OPENSSL_RAW_DATA, $iv);

            if (false === $decrypted) {
                throw new EncryptionException('解密失败: ' . openssl_error_string());
            }

            return $decrypted;
        } catch (\Exception $e) {
            throw new EncryptionException('解密过程中发生错误: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 准备加密密钥.
     *
     * @param string $key           原始密钥
     * @param string $hashAlgorithm 哈希算法
     * @return string 处理后的密钥
     */
    private static function prepareKey(string $key, string $hashAlgorithm): string
    {
        // 使用哈希算法确保密钥长度一致
        return hash($hashAlgorithm, $key, true);
    }

    /**
     * 生成随机密钥.
     *
     * @param int $length 密钥长度
     * @return string 生成的密钥
     * @throws EncryptionException
     */
    public static function generateKey(int $length = self::DEFAULT_KEY_LENGTH): string
    {
        try {
            $key = random_bytes($length);

            return base64_encode($key);
        } catch (\Exception $e) {
            throw new EncryptionException('生成密钥失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 哈希数据（单向加密）.
     *
     * @param string $data    要哈希的数据
     * @param array  $options 哈希选项
     * @return string 哈希后的字符串
     * @throws EncryptionException
     */
    public static function hash(string $data, array $options = []): string
    {
        try {
            $algorithm = $options['algorithm'] ?? self::DEFAULT_HASH_ALGORITHM;
            $salt      = $options['salt']      ?? '';

            // 如果提供了salt，则将其添加到数据前
            if (!empty($salt)) {
                $data = $salt . $data;
            }

            return hash($algorithm, $data);
        } catch (\Exception $e) {
            throw new EncryptionException('哈希过程中发生错误: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 验证哈希值
     *
     * @param string $data    原始数据
     * @param string $hash    要验证的哈希值
     * @param array  $options 哈希选项
     * @return bool 验证结果
     * @throws EncryptionException
     */
    public static function verifyHash(string $data, string $hash, array $options = []): bool
    {
        $computedHash = self::hash($data, $options);

        // 使用hash_equals防止时序攻击
        return hash_equals($computedHash, $hash);
    }

    /**
     * 加密URL参数.
     *
     * @param string $data 要加密的数据
     * @param string $key  密钥
     * @return string 加密后的URL安全字符串
     * @throws EncryptionException
     */
    public static function encryptUrlParam(string $data, string $key): string
    {
        $encrypted = self::encrypt($data, $key);

        // 将Base64编码转换为URL安全的格式
        return strtr($encrypted, '+/=', '-_,');
    }

    /**
     * 解密URL参数.
     *
     * @param string $data 要解密的数据
     * @param string $key  密钥
     * @return string 解密后的字符串
     * @throws EncryptionException
     */
    public static function decryptUrlParam(string $data, string $key): string
    {
        // 将URL安全格式转换回标准Base64
        $data = strtr($data, '-_,', '+/=');

        return self::decrypt($data, $key);
    }

    /**
     * 安全比较字符串（防止时序攻击）.
     *
     * @param string $a 第一个字符串
     * @param string $b 第二个字符串
     * @return bool 比较结果
     */
    public static function secureCompare(string $a, string $b): bool
    {
        return hash_equals($a, $b);
    }

    /**
     * 加密文件.
     *
     * @param string $inputFile  输入文件路径
     * @param string $outputFile 输出文件路径
     * @param string $key        密钥
     * @param array  $options    加密选项
     * @return bool 操作结果
     * @throws EncryptionException
     */
    public static function encryptFile(string $inputFile, string $outputFile, string $key, array $options = []): bool
    {
        try {
            if (!file_exists($inputFile)) {
                throw new InternalServerErrorException('输入文件不存在: ' . $inputFile);
            }

            // 读取文件内容
            $content = file_get_contents($inputFile);
            if (false === $content) {
                throw new InternalServerErrorException('无法读取输入文件: ' . $inputFile);
            }

            // 加密内容
            $encryptedContent = self::encrypt($content, $key, $options);

            // 写入加密后的内容
            $result = file_put_contents($outputFile, $encryptedContent);

            if (false === $result) {
                throw new InternalServerErrorException('无法写入输出文件: ' . $outputFile);
            }

            return true;
        } catch (\Exception $e) {
            throw new EncryptionException('加密文件过程中发生错误: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 解密文件.
     *
     * @param string $inputFile  输入文件路径
     * @param string $outputFile 输出文件路径
     * @param string $key        密钥
     * @param array  $options    解密选项
     * @return bool 操作结果
     * @throws EncryptionException
     */
    public static function decryptFile(string $inputFile, string $outputFile, string $key, array $options = []): bool
    {
        try {
            if (!file_exists($inputFile)) {
                throw new EncryptionException('输入文件不存在: ' . $inputFile);
            }

            // 读取文件内容
            $content = file_get_contents($inputFile);
            if (false === $content) {
                throw new EncryptionException('无法读取输入文件: ' . $inputFile);
            }

            // 解密内容
            $decryptedContent = self::decrypt($content, $key, $options);

            // 写入解密后的内容
            $result = file_put_contents($outputFile, $decryptedContent);

            if (false === $result) {
                throw new EncryptionException('无法写入输出文件: ' . $outputFile);
            }

            return true;
        } catch (\Exception $e) {
            throw new InternalServerErrorException('解密文件过程中发生错误: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
