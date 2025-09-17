<?php

namespace Tests\Support\Traits;

use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * 用于测试AutoFillProperties trait的最小化测试类.
 */
class MinimalTestClass
{
    use AutoFillProperties;

    // 用于测试参数是否被正确设置到内部变量
    public function getParams(): array
    {
        try {
            return $this->getAutoFillHandler()->getParams();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getProperties(): array
    {
        try {
            return $this->getAutoFillHandler()->getProperties();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getAlias(): array
    {
        try {
            return $this->getAutoFillHandler()->getAlias();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getCache(): ?\Rice\Basic\Contracts\CacheContract
    {
        try {
            return $this->getAutoFillHandler()->getCache();
        } catch (\Exception $e) {
            return null;
        }
    }
}
