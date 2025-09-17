<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\FrameTypeUtil;
use Rice\Basic\Support\Utils\ReflectionCache;
use Rice\Basic\Components\Exception\InternalServerErrorException;

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
     * 存储类支持的特性标识.
     * @var array
     */
    protected static array $_supportedFeatures = [];

    /**
     * 特性优先级配置.
     * @var array
     */
    protected static array $_featurePriorities = [];

    /**
     * 默认特性优先级.
     * @var array
     */
    protected static array $_defaultFeaturePriorities = [
        'laravel'   => 10,       // Laravel环境处理器
        'macroable' => 99,     // Macroable处理器
        'accessor'  => 100,     // Accessor处理器
        'default'   => 150,       // 默认处理器
    ];

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
     * 设置特性优先级.
     * @param string $feature  特性名称
     * @param int    $priority 优先级值，数字越小优先级越高
     * @return void
     */
    public static function setFeaturePriority(string $feature, int $priority): void
    {
        $className = static::class;
        if (!isset(self::$_featurePriorities[$className])) {
            self::$_featurePriorities[$className] = [];
        }
        self::$_featurePriorities[$className][$feature] = $priority;
    }

    /**
     * 获取特性优先级.
     * @param string $feature 特性名称
     * @return int
     */
    public static function getFeaturePriority(string $feature): int
    {
        $className = static::class;
        // 优先使用自定义优先级，其次使用默认优先级
        if (isset(self::$_featurePriorities[$className])
            && isset(self::$_featurePriorities[$className][$feature])) {
            return self::$_featurePriorities[$className][$feature];
        }

        return self::$_defaultFeaturePriorities[$feature] ?? 100;
    }

    /**
     * 设置类支持的特性标识.
     * @param string $feature 特性名称
     * @return void
     */
    public static function setSupportedFeature(string $feature): void
    {
        $className = static::class;
        if (!isset(self::$_supportedFeatures[$className])) {
            self::$_supportedFeatures[$className] = [];
        }
        self::$_supportedFeatures[$className][$feature] = true;
    }

    /**
     * 检查类是否支持特定特性.
     * @param string $feature 特性名称
     * @return bool
     */
    public static function hasSupportedFeature(string $feature): bool
    {
        $className = static::class;

        return isset(self::$_supportedFeatures[$className])
               && isset(self::$_supportedFeatures[$className][$feature])
               && true === self::$_supportedFeatures[$className][$feature];
    }

    /**
     * 注册魔术方法处理器.
     *
     * @param string   $magicMethod      魔术方法名（如 '__call', '__get', '__set' 等）
     * @param \Closure $handler          处理器函数
     * @param int      $priority         优先级，数字越小优先级越高
     * @param bool     $skipParentResult 是否跳过父类方法的结果直接处理
     * @return void
     */
    public static function registerMagicMethodHandler(
        string $magicMethod,
        \Closure $handler,
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

        // 如果处理器需要跳过父类方法的结果，使用特殊数组标记
        $handlerToRegister = $skipParentResult
            ? static::markSkipParentResultHandler($handler)
            : $handler;

        self::$_magicMethodHandlers[$className][$magicMethod][$priority] = $handlerToRegister;
        ksort(self::$_magicMethodHandlers[$className][$magicMethod]);
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
     * 初始化trait特性标识
     * 用于在类初始化时设置所有支持的特性标识.
     *
     * @return void
     */
    private function initializeTraits(): void
    {
        // 检查并设置Accessor特性标识 - 使用属性标识替代方法检查
        if (property_exists($this, '_hasAccessor') && $this->_hasAccessor) {
            self::setSupportedFeature('accessor');
        }

        // 检查并设置Macroable特性标识 - 使用属性标识替代方法和属性检查
        if (property_exists($this, '_hasMacroable') && $this->_hasMacroable) {
            self::setSupportedFeature('macroable');
        }

        // 检查并设置Laravel特性标识
        if (FrameTypeUtil::isLaravel()) {
            self::setSupportedFeature('laravel');
        }
    }

    /**
     * 注册默认的魔术方法处理器.
     *
     * @return void
     */
    private function registerDefaultHandlers(): void
    {
        // 初始化trait特性标识
        $this->initializeTraits();

        // 注册各个类的魔术方法处理器
        $this->registerDefaultSetHandler();
        $this->registerDefaultGetHandler();
        $this->registerAccessorHandler();
        $this->registerMacroableHandler();
        $this->registerLaravelHandler();
    }

    /**
     * 注册Laravel环境下的处理器
     * 用于支持在Laravel环境中从app容器获取实例并调用方法.
     *
     * @return void
     */
    private function registerLaravelHandler(): void
    {
        // 仅在Laravel环境下注册该处理器
        if (self::hasSupportedFeature('laravel')) {
            // 为__call魔术方法注册处理器
            self::registerMagicMethodHandler('__call', function ($name, $arguments) {
                // 检查方法是否存在于app容器中的实例
                if (function_exists('app') && method_exists('app', 'has') && app()->has(static::class)) {
                    $instance = app(static::class);
                    if (method_exists($instance, $name)) {
                        return $instance->$name(...$arguments);
                    }
                }

                // 如果不满足条件，返回继续标记，让其他处理器处理
                return static::$MAGIC_METHOD_CONTINUE;
            }, self::getFeaturePriority('laravel')); // 使用特性优先级

            // 如果有registerSingleton方法，则为__callStatic魔术方法注册处理器
            if (method_exists($this, 'registerSingleton')) {
                self::registerMagicMethodHandler('__callStatic', function ($method, $parameters) {
                    if (FrameTypeUtil::isLaravel() && function_exists('app') && method_exists('app', 'has') && app()->has(static::class)) {
                        $instance = app(static::class);
                        if (method_exists($instance, $method)) {
                            return $instance->$method(...$parameters);
                        }
                    }

                    return static::$MAGIC_METHOD_CONTINUE;
                }, self::getFeaturePriority('default'));
            }
        }
    }

    /**
     * 注册默认的__set方法处理器
     * 用于直接处理属性设置.
     *
     * @return void
     */
    private function registerDefaultSetHandler(): void
    {
        self::registerMagicMethodHandler('__set', function ($name, $value) {
            // 直接设置属性值，PHP会自动处理公共属性
            try {
                $this->{$name} = $value;

                return true;
            } catch (\Throwable $e) {
                // 如果直接设置失败，可能是私有/受保护属性，我们可以选择抛出异常或返回继续标记
                // 这里我们选择返回继续标记，让其他处理器有机会处理
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, 50);
    }

    /**
     * 注册默认的__get方法处理器
     * 用于直接处理属性获取.
     *
     * @return void
     */
    private function registerDefaultGetHandler(): void
    {
        self::registerMagicMethodHandler('__get', function ($name) {
            // 直接获取属性值，PHP会自动处理公共属性
            try {
                return $this->{$name};
            } catch (\Throwable $e) {
                // 如果直接获取失败，可能是私有/受保护属性或不存在的属性
                // 这里我们选择返回继续标记，让其他处理器有机会处理
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, 50);
    }

    /**
     * 注册Accessor的getter/setter处理器
     * 用于支持Accessor特性的类.
     *
     * @return void
     */
    private function registerAccessorHandler(): void
    {
        if (self::hasSupportedFeature('accessor')) {
            self::registerMagicMethodHandler('__call', function ($name, $args) {
                // 避免使用resetAccessor方法，防止trait方法冲突
                // 直接从实例获取getter和setter的状态
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
            }, self::getFeaturePriority('accessor'));
        }
    }

    /**
     * 注册Macroable的宏方法处理器
     * 用于支持Macroable特性的类.
     *
     * @return void
     */
    private function registerMacroableHandler(): void
    {
        if (self::hasSupportedFeature('macroable')) {
            // 注册__call处理器（实例方法）
            self::registerMagicMethodHandler('__call', function ($method, $parameters) {
                if (static::hasMacro($method)) {
                    if (static::$macros[$method] instanceof \Closure) {
                        $boundClosure = static::$macros[$method]->bindTo($this, static::class);

                        return call_user_func_array($boundClosure, $parameters);
                    }

                    return call_user_func_array(static::$macros[$method], $parameters);
                }

                return static::$MAGIC_METHOD_CONTINUE;
            }, self::getFeaturePriority('macroable'));

            // 注册__callStatic处理器（静态方法）
            self::registerMagicMethodHandler('__callStatic', function ($method, $parameters) {
                if (static::hasMacro($method)) {
                    $macro = static::$macros[$method];

                    if ($macro instanceof \Closure) {
                        $macro = $macro->bindTo(null, static::class);
                    }

                    return call_user_func_array($macro, $parameters);
                }

                return static::$MAGIC_METHOD_CONTINUE;
            }, self::getFeaturePriority('macroable'));
        }
    }

    /**
     * 注册AutoRegisterSingleton的处理器
     * 用于支持AutoRegisterSingleton特性的类.
     *
     * @return void
     */

    /**
     * 统一的__call魔术方法实现.
     *
     * @param string $name      方法名
     * @param array  $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    public function __call($name, $arguments)
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
    public static function __callStatic($name, $arguments)
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
    public function __get($name)
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
    public function __set($name, $value)
    {
        $this->handleMagicMethod('__set', [$name, $value]);
    }
}
