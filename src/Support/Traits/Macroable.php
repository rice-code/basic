<?php

namespace Rice\Basic\Support\Traits;

use Closure;
use Rice\Basic\Components\Exception\InternalServerErrorException;
use Rice\Basic\Support\Traits\MagicMethodManager;

trait Macroable
{
    use MagicMethodManager;

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
     * @param string $name
     * @return bool
     */
    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }


}
