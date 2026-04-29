<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\FrameTypeUtil;
use Rice\Basic\Support\Utils\ReflectionCache;
use Rice\Basic\Infrastructure\Exception\InternalServerErrorException;
use Rice\Basic\Support\Traits\MagicMethod\HandlerInterface;

/**
 * 魔术方法管理器
 * 用于统一管理项目中的魔术方法，避免多个trait使用魔术方法时的冲突
 * 提供与Laravel框架的兼容性支持
 */
trait MagicMethodManager
{
    /**
     * 魔术方法处理器集合
     * 存储结构: [className][magicMethod][priority] = handler.
     * @var array
     */
    private static array $_magicMethodHandlers = [];

    /**
     * 魔术方法调用标记，用于指示处理器希望继续执行下一个处理器.
     * @var string
     */
    protected static string $MAGIC_METHOD_CONTINUE = '__MAGIC_METHOD_CONTINUE__';

    /**
     * 魔术方法调用标记，用于指示处理器希望直接执行而不使用父类方法的结果.
     * @var string
     */
    protected static string $MAGIC_METHOD_SKIP_PARENT_RESULT = '__MAGIC_METHOD_SKIP_PARENT_RESULT__';

    /**
     * 注册魔术方法处理器（支持 HandlerInterface 实例或闭包）
     *
     * @param string          $magicMethod      魔术方法名（如 '__call', '__get', '__set' 等）
     * @param \Closure|HandlerInterface $handler 处理器
     * @param int             $priority         优先级，数字越小优先级越高
     * @param bool            $skipParentResult 是否跳过父类方法的结果直接处理
     * @return void
     */
    public static function registerMagicMethodHandler(
        string $magicMethod,
        $handler,
        int $priority = 100,
        bool $skipParentResult = false
    ): void {
        $className = static::class;

        if (!isset(self::$_magicMethodHandlers[$className])) {
            self::$_magicMethodHandlers[$className] = [];
        }

        if (!isset(self::$_magicMethodHandlers[$className][$magicMethod])) {
            self::$_magicMethodHandlers[$className][$magicMethod] = [];
        }

        // 如果是 HandlerInterface 实例，使用其优先级和 skipParentResult 设置
        if ($handler instanceof HandlerInterface) {
            $priority         = $handler->getPriority();
            $skipParentResult = $handler->skipParentResult();
        }

        // 如果处理器需要跳过父类方法的结果，使用特殊数组标记
        $handlerToRegister = $skipParentResult
            ? [static::$MAGIC_METHOD_SKIP_PARENT_RESULT, $handler]
            : $handler;

        self::$_magicMethodHandlers[$className][$magicMethod][$priority] = $handlerToRegister;
        ksort(self::$_magicMethodHandlers[$className][$magicMethod]);
    }

    /**
     * 注册 HandlerInterface 实例
     *
     * @param HandlerInterface $handler 处理器实例
     * @return void
     */
    public static function registerHandler(HandlerInterface $handler): void
    {
        $className = static::class;

        // 为处理器支持的所有魔术方法注册
        foreach (['__call', '__callStatic', '__get', '__set', '__isset', '__unset', '__toString'] as $magicMethod) {
            if ($handler->supports($magicMethod)) {
                static::registerMagicMethodHandler(
                    $magicMethod,
                    function (...$args) use ($handler) {
                        return $handler->handle($this, $args);
                    },
                    $handler->getPriority(),
                    $handler->skipParentResult()
                );
            }
        }
    }

    /**
     * 处理魔术方法调用.
     *
     * @param string $magicMethod 魔术方法名
     * @param array  $arguments   方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    private function handleMagicMethod(string $magicMethod, array $arguments)
    {
        $className = static::class;

        // 检查并确保有处理器
        $this->ensureHandlersExist($magicMethod);

        // 性能优化：直接获取处理器数组，避免额外的函数调用
        $handlers = self::$_magicMethodHandlers[$className][$magicMethod];
        // 初始化变量
        $hasProcessed = false;
        $finalResult  = null;

        // 第一遍：执行跳过父类结果的处理器（避免两次遍历数组）
        foreach ($handlers as $handler) {
            if (static::isSkipParentResultHandler($handler)) {
                $originalHandler = static::getOriginalHandler($handler);

                try {
                    $result       = call_user_func_array($originalHandler->bindTo($this, static::class), $arguments);
                    $hasProcessed = true;

                    // 如果处理器返回了非CONTINUE标记，则直接返回结果
                    if ($result !== static::$MAGIC_METHOD_CONTINUE) {
                        return $result;
                    }
                } catch (\Throwable $e) {
                    // 处理器抛出异常时继续尝试下一个处理器
                    continue;
                }
            }
        }

        // 如果跳过父类结果的处理器没有返回结果，获取父类方法结果
        $parentResult = $this->tryInvokeParentMagicMethod($magicMethod, $arguments);

        // 第二遍：执行使用父类结果的处理器
        $extendedArguments = array_merge($arguments, [$parentResult]);
        foreach ($handlers as $handler) {
            if (!static::isSkipParentResultHandler($handler)) {
                try {
                    $result       = call_user_func_array($handler->bindTo($this, static::class), $extendedArguments);

                    $hasProcessed = true;

                    if ($result !== static::$MAGIC_METHOD_CONTINUE) {
                        return $result;
                    }
                } catch (\Throwable $e) {
                    // 处理器抛出异常时继续尝试下一个处理器
                    continue;
                }
            }
        }

        // 如果没有自定义处理器的结果，返回父类方法的结果
        if (null === $finalResult && null !== $parentResult) {
            return $parentResult;
        }

        // 返回结果或抛出异常
        if (!$hasProcessed) {
            throw new InternalServerErrorException("Method {$magicMethod} does not exist.");
        }

        return $finalResult;
    }

    /**
     * 标记跳过父类结果的处理器.
     * @param \Closure $handler 原始处理器
     * @return array 包装后的处理器数组
     */
    protected static function markSkipParentResultHandler(\Closure $handler): array
    {
        return [static::$MAGIC_METHOD_SKIP_PARENT_RESULT, $handler];
    }

    /**
     * 检查一个处理器是否是跳过父类结果的处理器.
     * @param mixed $handler 要检查的处理器
     * @return bool 是否是跳过父类结果的处理器
     */
    protected static function isSkipParentResultHandler($handler): bool
    {
        return is_array($handler) && isset($handler[0]) && $handler[0] === static::$MAGIC_METHOD_SKIP_PARENT_RESULT;
    }

    /**
     * 从包装中获取原始处理器.
     * @param array $wrappedHandler 包装后的处理器数组
     * @return \Closure 原始处理器
     */
    protected static function getOriginalHandler(array $wrappedHandler): \Closure
    {
        return $wrappedHandler[1];
    }

    /**
     * 确保处理器存在，如果不存在则尝试注册默认处理器.
     *
     * @param  string                       $magicMethod 魔术方法名
     * @throws InternalServerErrorException
     */
    private function ensureHandlersExist(string $magicMethod): void
    {
        $className = static::class;

        if (!isset(self::$_magicMethodHandlers[$className], self::$_magicMethodHandlers[$className][$magicMethod])) {
            // 动态注册默认处理器
            $this->registerDefaultHandlers();

            // 如果还是没有处理器，则抛出异常
            $hasHandlers = isset(
                self::$_magicMethodHandlers[$className],
                self::$_magicMethodHandlers[$className][$magicMethod]
            );
            if (!$hasHandlers) {
                throw new InternalServerErrorException("Method {$magicMethod} does not exist.");
            }
        }
    }

    /**
     * 注册默认的魔术方法处理器.
     *
     * @return void
     */
    private function registerDefaultHandlers(): void
    {
        // 注册默认处理器（使用策略类）
        $this->registerDefaultGetHandler();
        $this->registerDefaultSetHandler();
        $this->registerAccessorHandler();
        $this->registerMacroableHandler();
        $this->registerLaravelHandler();
    }

    /**
     * 注册默认的 __get 处理器
     *
     * @return void
     */
    private function registerDefaultGetHandler(): void
    {
        $handler = new MagicMethod\DefaultGetHandler();
        self::registerMagicMethodHandler('__get', function ($name) {
            try {
                return $this->{$name};
            } catch (\Throwable $e) {
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, $handler->getPriority());
    }

    /**
     * 注册默认的 __set 处理器
     *
     * @return void
     */
    private function registerDefaultSetHandler(): void
    {
        $handler = new MagicMethod\DefaultSetHandler();
        self::registerMagicMethodHandler('__set', function ($name, $value) {
            try {
                $this->{$name} = $value;
                return true;
            } catch (\Throwable $e) {
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, $handler->getPriority());
    }

    /**
     * 注册 Accessor 的 getter/setter 处理器
     *
     * @return void
     */
    private function registerAccessorHandler(): void
    {
        // 检查是否支持 Accessor 特性
        if (!property_exists($this, '_hasAccessor') || !$this->_hasAccessor) {
            return;
        }

        $handler = new MagicMethod\AccessorHandler();
        self::registerMagicMethodHandler('__call', function ($name, $args) {
            $pattern = $this->getAuth();
            $matches = [];
            preg_match($pattern, $name, $matches);

            $style    = $matches[1] ?? null;
            $attrName = $matches[2] ?? null;

            if (is_null($style) || is_null($attrName)) {
                return static::$MAGIC_METHOD_CONTINUE;
            }

            $attrName = lcfirst($attrName);

            if (!property_exists($this, $attrName)) {
                return static::$MAGIC_METHOD_CONTINUE;
            }

            switch ($style) {
                case 'set':
                    $this->setValue($attrName, $args);
                    return $this;
                case 'get':
                    return $this->getValue($attrName);
            }

            return static::$MAGIC_METHOD_CONTINUE;
        }, $handler->getPriority());
    }

    /**
     * 注册 Macroable 的宏方法处理器
     *
     * @return void
     */
    private function registerMacroableHandler(): void
    {
        // 检查是否支持 Macroable 特性
        if (!property_exists($this, '_hasMacroable') || !$this->_hasMacroable) {
            return;
        }

        $handler = new MagicMethod\MacroableHandler();

        // 注册 __call 处理器（实例方法）
        self::registerMagicMethodHandler('__call', function ($method, $parameters) {
            if (static::hasMacro($method)) {
                if (static::$macros[$method] instanceof \Closure) {
                    $boundClosure = static::$macros[$method]->bindTo($this, static::class);
                    return call_user_func_array($boundClosure, $parameters);
                }
                return call_user_func_array(static::$macros[$method], $parameters);
            }
            return static::$MAGIC_METHOD_CONTINUE;
        }, $handler->getPriority());

        // 注册 __callStatic 处理器（静态方法）
        self::registerMagicMethodHandler('__callStatic', function ($method, $parameters) {
            if (static::hasMacro($method)) {
                $macro = static::$macros[$method];
                if ($macro instanceof \Closure) {
                    $macro = $macro->bindTo(null, static::class);
                }
                return call_user_func_array($macro, $parameters);
            }
            return static::$MAGIC_METHOD_CONTINUE;
        }, $handler->getPriority());
    }

    /**
     * 注册 Laravel 环境下的处理器
     *
     * @return void
     */
    private function registerLaravelHandler(): void
    {
        // 仅在 Laravel 环境下注册该处理器
        if (!FrameTypeUtil::isLaravel()) {
            return;
        }

        $handler = new MagicMethod\LaravelHandler();

        // 为 __call 魔术方法注册处理器
        self::registerMagicMethodHandler('__call', function ($name, $arguments) {
            if (function_exists('app') && method_exists('app', 'has') && app()->has(static::class)) {
                $instance = app(static::class);
                if (method_exists($instance, $name)) {
                    return $instance->$name(...$arguments);
                }
            }
            return static::$MAGIC_METHOD_CONTINUE;
        }, $handler->getPriority());

        // 如果有 registerSingleton 方法，则为 __callStatic 魔术方法注册处理器
        if (method_exists($this, 'registerSingleton')) {
            self::registerMagicMethodHandler('__callStatic', function ($method, $parameters) {
                if (FrameTypeUtil::isLaravel() && function_exists('app') && method_exists('app', 'has') && app()->has(static::class)) {
                    $instance = app(static::class);
                    if (method_exists($instance, $method)) {
                        return $instance->$method(...$parameters);
                    }
                }
                return static::$MAGIC_METHOD_CONTINUE;
            }, $handler->getPriority());
        }
    }

    /**
     * 尝试调用父类魔术方法.
     *
     * @param string $magicMethod 魔术方法名
     * @param array  $arguments   方法参数
     * @return mixed 方法调用结果，如果调用失败则返回null
     */
    private function tryInvokeParentMagicMethod(string $magicMethod, array $arguments)
    {
        try {
            $parentClass = get_parent_class($this);
            if ($parentClass && method_exists($parentClass, $magicMethod)) {
                // 使用switch统一处理所有魔术方法
                switch ($magicMethod) {
                    case '__callStatic':
                        // 静态魔术方法特殊处理
                        // @phpstan-ignore-next-line
                        return parent::__callStatic($arguments[0], $arguments[1]);
                    case '__call':
                        // @phpstan-ignore-next-line
                        return parent::__call($arguments[0], $arguments[1]);
                    case '__get':
                        // @phpstan-ignore-next-line
                        return parent::__get($arguments[0]);
                    case '__set':
                        // @phpstan-ignore-next-line
                        parent::__set($arguments[0], $arguments[1]);

                        return null;
                    default:
                        // 其他魔术方法使用反射确保兼容性
                        return $this->invokeParentMagicMethodWithReflection(
                            $parentClass,
                            $magicMethod,
                            $this,
                            $arguments
                        );
                }
            }
        } catch (\Throwable $e) {
            // 调用父类方法失败时返回null
        }

        return null;
    }

    /**
     * 使用反射机制安全调用父类魔术方法.
     *
     * @param string $parentClass 父类名
     * @param string $magicMethod 魔术方法名
     * @param mixed  $instance    实例对象（静态方法可为null）
     * @param array  $arguments   方法参数
     * @return mixed 方法调用结果
     */
    protected function invokeParentMagicMethodWithReflection(
        string $parentClass,
        string $magicMethod,
        $instance,
        array $arguments
    ) {
        // 使用反射缓存获取反射方法对象
        $reflectionMethod = ReflectionCache::getMethod($parentClass, $magicMethod);

        return $reflectionMethod->invokeArgs($instance, $arguments);
    }

    /**
     * 统一的__call魔术方法实现.
     *
     * @param string $name      方法名
     * @param array  $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    public function __call(string $name, array $arguments)
    {
        // 调用统一的魔术方法处理器
        return $this->handleMagicMethod('__call', [$name, $arguments]);
    }

    /**
     * 统一的__callStatic魔术方法实现.
     *
     * @param string $name      方法名
     * @param array  $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // 在静态方法中创建临时实例来调用实例方法
        return (new static())->handleMagicMethod('__callStatic', [$name, $arguments]);
    }

    /**
     * 统一的__get魔术方法实现.
     *
     * @param string $name 属性名
     * @return mixed
     * @throws InternalServerErrorException
     */
    public function __get(string $name)
    {
        return $this->handleMagicMethod('__get', [$name]);
    }

    /**
     * 统一的__set魔术方法实现.
     *
     * @param string $name  属性名
     * @param mixed  $value 属性值
     * @return void
     * @throws InternalServerErrorException
     */
    public function __set(string $name, $value)
    {
        $this->handleMagicMethod('__set', [$name, $value]);
    }
}
