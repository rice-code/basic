<?php

namespace Rice\Basic\Support\Utils;

class MethodExistsUtil
{
    /**
     * 存在设置访问权限函数则执行.
     *
     * @param object $obj
     * @return void
     */
    public static function resetAccessor(object $obj): void
    {
        if (method_exists($obj, 'resetAccessor')) {
            $obj->resetAccessor();
        }
    }

    /**
     * 容器内注册单例.
     *
     * @param object $obj
     * @return void
     */
    public static function registerSingleton(object $obj): void
    {
        if (method_exists($obj, 'registerSingleton')) {
            $obj->registerSingleton();
        }
    }
}
