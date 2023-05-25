<?php

namespace Rice\Basic\Support\Traits;

use Closure;
use Rice\Basic\Components\Exception\SupportException;

trait Macroable
{
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

    /**
     * @param string $method
     * @param array  $parameters
     * @return mixed
     * @throws SupportException
     */
    public static function __callStatic(string $method, array $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new SupportException("Method {$method} does not exist.");
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$macros[$method], null, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    /**
     * @param string $method
     * @param array  $parameters
     * @return mixed
     * @throws SupportException
     */
    public function __call(string $method, array $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new SupportException("Method {$method} does not exist.");
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }
}
