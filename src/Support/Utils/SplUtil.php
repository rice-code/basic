<?php

namespace Rice\Basic\Support\Utils;

class SplUtil
{
    /**
     * 递归获取所有类中使用过的 trait 类
     *
     * @param $class
     * @return array|mixed
     */
    public static function classUsesRecursive($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $newClass) {
            $results += self::traitUsesRecursive($newClass);
        }

        return array_unique($results);
    }

    /**
     * 递归获取 trait 类中所有的 trait 类
     *
     * @param $trait
     * @return array|false|string[]
     */
    public static function traitUsesRecursive($trait)
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $newTrait) {
            $traits += self::traitUsesRecursive($newTrait);
        }

        return $traits;
    }
}