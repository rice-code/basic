<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

class MacroableHandler extends AbstractHandler
{
    /**
     * 优先级：99（高于 Accessor）
     *
     * @var int
     */
    protected int $priority = 99;

    /**
     * 检查是否支持指定的魔术方法
     *
     * @param string $magicMethod 魔术方法名
     * @return bool
     */
    public function supports(string $magicMethod): bool
    {
        return in_array($magicMethod, ['__call', '__callStatic']);
    }

    /**
     * 处理魔术方法调用
     *
     * @param object $instance 调用魔术方法的对象实例
     * @param array  $arguments 方法参数
     * @return mixed
     */
    public function handle(object $instance, array $arguments)
    {
        $method     = $arguments[0];
        $parameters = $arguments[1] ?? [];

        // 检查实例是否有 hasMacro 方法
        if (!method_exists($instance, 'hasMacro')) {
            return $this->continue();
        }

        // 检查是否为静态方法调用
        $isStatic = strpos(strtolower(get_class($instance)), 'static') !== false;

        if (!($instance::hasMacro($method))) {
            return $this->continue();
        }

        // 获取类的静态属性 $macros
        $class = get_class($instance);
        if (!property_exists($class, 'macros')) {
            return $this->continue();
        }

        $macro = $class::$macros[$method] ?? null;

        if ($macro instanceof \Closure) {
            if ($isStatic) {
                $boundClosure = $macro->bindTo(null, $class);
            } else {
                $boundClosure = $macro->bindTo($instance, $class);
            }

            return call_user_func_array($boundClosure, $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }
}
