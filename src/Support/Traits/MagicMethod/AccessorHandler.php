<?php

namespace Rice\Basic\Support\Traits\MagicMethod;

class AccessorHandler extends AbstractHandler
{
    /**
     * 优先级：100（默认优先级）
     *
     * @var int
     */
    protected int $priority = 100;

    /**
     * 检查是否支持指定的魔术方法
     *
     * @param string $magicMethod 魔术方法名
     * @return bool
     */
    public function supports(string $magicMethod): bool
    {
        return $magicMethod === '__call';
    }

    /**
     * 处理 __call 魔术方法调用
     *
     * @param object $instance 调用魔术方法的对象实例
     * @param array  $arguments 方法参数
     * @return mixed
     */
    public function handle(object $instance, array $arguments)
    {
        $name = $arguments[0];
        $args = $arguments[1] ?? [];

        // 检查实例是否有 getAuth 方法
        if (!method_exists($instance, 'getAuth')) {
            return $this->continue();
        }

        $pattern = $instance->getAuth();
        $matches = [];
        preg_match($pattern, $name, $matches);

        $style    = $matches[1] ?? null;
        $attrName = $matches[2] ?? null;

        if (is_null($style) || is_null($attrName)) {
            return $this->continue();
        }

        $attrName = lcfirst($attrName);

        if (!property_exists($instance, $attrName)) {
            return $this->continue();
        }

        switch ($style) {
            case 'set':
                if (method_exists($instance, 'setValue')) {
                    $instance->setValue($attrName, $args);
                }

                return $instance;
            case 'get':
                if (method_exists($instance, 'getValue')) {
                    return $instance->getValue($attrName);
                }
        }

        return $this->continue();
    }
}
