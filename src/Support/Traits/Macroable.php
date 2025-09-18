<?php

namespace Rice\Basic\Support\Traits;

trait Macroable
{
    use MagicMethodManager;
    /**
     * Macroable特性标识
     * 用于标记类使用了Macroable特性，以便在MagicMethodManager中识别.
     *
     * @internal
     * @var bool
     */
    protected bool $_hasMacroable = true;

    /**
     * @var array
     */
    protected static $macros = [];

    /**
     * @param string   $name
     * @param callable $macro
     * @return void
     */
    public static function macro(string $name, callable $macro): void
    {
        static::$macros[$name] = $macro;
    }

    /**
     * registerMacro方法 - 作为macro方法的别名，保持向后兼容性.
     *
     * @param string   $name
     * @param callable $macro
     * @return void
     */
    public static function registerMacro(string $name, callable $macro): void
    {
        static::macro($name, $macro);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }
}
