<?php

namespace Rice\Basic\Support\Traits;

use Closure;
use Rice\Basic\Components\Exception\InternalServerErrorException;
use Rice\Basic\Support\Utils\FrameTypeUtil;

/**
 * 魔术方法管理器
 * 用于统一管理项目中的魔术方法，避免多个trait使用魔术方法时的冲突
 * 提供与Laravel框架的兼容性支持
 */
trait MagicMethodManager
{
    /**
     * 魔术方法处理器集合
     * 存储结构: [className][magicMethod][priority] = handler
     * @var array
     */
    private static array $_magicMethodHandlers = [];
    
    /**
     * 魔术方法调用标记，用于指示处理器希望继续执行下一个处理器
     * @var string
     */
    protected static string $MAGIC_METHOD_CONTINUE = '__MAGIC_METHOD_CONTINUE__';
    
    /**
     * 注册魔术方法处理器
     * 
     * @param string $magicMethod 魔术方法名（如 '__call', '__get', '__set' 等）
     * @param Closure $handler 处理器函数
     * @param int $priority 优先级，数字越小优先级越高
     * @return void
     */
    public static function registerMagicMethodHandler(string $magicMethod, Closure $handler, int $priority = 100): void
    {
        $className = static::class;
        
        if (!isset(self::$_magicMethodHandlers[$className])) {
            self::$_magicMethodHandlers[$className] = [];
        }
        
        if (!isset(self::$_magicMethodHandlers[$className][$magicMethod])) {
            self::$_magicMethodHandlers[$className][$magicMethod] = [];
        }
        
        self::$_magicMethodHandlers[$className][$magicMethod][$priority] = $handler;
        ksort(self::$_magicMethodHandlers[$className][$magicMethod]);
    }
    
    /**
     * 处理魔术方法调用
     * 
     * @param string $magicMethod 魔术方法名
     * @param array $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    private function handleMagicMethod(string $magicMethod, array $arguments): mixed
    {
        $className = static::class;
        
        // 检查是否有注册的处理器
        if (!isset(self::$_magicMethodHandlers[$className]) || !isset(self::$_magicMethodHandlers[$className][$magicMethod])) {
            // 动态注册默认处理器
            $this->registerDefaultHandlers();
            
            // 如果还是没有处理器，则抛出异常
            if (!isset(self::$_magicMethodHandlers[$className]) || !isset(self::$_magicMethodHandlers[$className][$magicMethod])) {
                throw new InternalServerErrorException("Method {$magicMethod} does not exist.");
            }
        }
        
        // 按优先级顺序尝试处理器
        foreach (self::$_magicMethodHandlers[$className][$magicMethod] as $handler) {
            try {
                $result = call_user_func_array($handler->bindTo($this, static::class), $arguments);
                // 如果处理器返回了非MAGIC_METHOD_CONTINUE标记，则返回结果
                if ($result !== static::$MAGIC_METHOD_CONTINUE) {
                    return $result;
                }
            } catch (\Throwable $e) {
                // 处理器抛出异常时继续尝试下一个处理器
                continue;
            }
        }
        
        // 尝试调用父类的魔术方法（如果存在）
        try {
            $parentClass = get_parent_class($this);
            if ($parentClass && method_exists($parentClass, $magicMethod)) {
                // 使用switch统一处理所有魔术方法
                switch ($magicMethod) {
                    case '__callStatic':
                        // 静态魔术方法特殊处理
                        $reflectionMethod = new \ReflectionMethod($parentClass, $magicMethod);
                        $reflectionMethod->setAccessible(true);
                        return $reflectionMethod->invokeArgs(null, $arguments);
                    case '__call':
                        return parent::__call($arguments[0], $arguments[1]);
                    case '__get':
                        return parent::__get($arguments[0]);
                    case '__set':
                        parent::__set($arguments[0], $arguments[1]);
                        return null;
                    default:
                        // 其他魔术方法使用反射确保兼容性
                        $reflectionMethod = new \ReflectionMethod($parentClass, $magicMethod);
                        $reflectionMethod->setAccessible(true);
                        return $reflectionMethod->invokeArgs($this, $arguments);
                }
            }
        } catch (\Exception $e) {
            // 调用失败时继续抛出原始异常
            throw $e;
        }
        
        // 所有处理器都返回了继续，或者都失败了
        throw new InternalServerErrorException("Method {$magicMethod} does not exist.");
    }
    
    /**
     * 注册默认的魔术方法处理器
     * 
     * @return void
     */
    private function registerDefaultHandlers(): void
    {
        // 注册各个类的魔术方法处理器
        $this->registerDefaultSetHandler();
        $this->registerDefaultGetHandler();
        $this->registerAccessorHandler();
        $this->registerMacroableHandler();
        $this->registerAutoRegisterSingletonHandler();
    }

    /**
     * 注册默认的__set方法处理器
     * 用于直接处理属性设置
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
            } catch (\Error|\Exception $e) {
                // 如果直接设置失败，可能是私有/受保护属性，我们可以选择抛出异常或返回继续标记
                // 这里我们选择返回继续标记，让其他处理器有机会处理
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, 50);
    }

    /**
     * 注册默认的__get方法处理器
     * 用于直接处理属性获取
     * 
     * @return void
     */
    private function registerDefaultGetHandler(): void
    {
        self::registerMagicMethodHandler('__get', function ($name) {
            // 直接获取属性值，PHP会自动处理公共属性
            try {
                return $this->{$name};
            } catch (\Error|\Exception $e) {
                // 如果直接获取失败，可能是私有/受保护属性或不存在的属性
                // 这里我们选择返回继续标记，让其他处理器有机会处理
                return static::$MAGIC_METHOD_CONTINUE;
            }
        }, 50);
    }

    /**
     * 注册Accessor的getter/setter处理器
     * 用于支持Accessor特性的类
     * 
     * @return void
     */
    private function registerAccessorHandler(): void
    {
        if (method_exists($this, 'getAuth') && method_exists($this, 'setValue') && method_exists($this, 'getValue')) {
            self::registerMagicMethodHandler('__call', function ($name, $args) {
                // 避免使用resetAccessor方法，防止trait方法冲突
                // 直接从实例获取getter和setter的状态
                $pattern = $this->getAuth();
                $matches = [];
                preg_match($pattern, $name, $matches);

                $style = $matches[1] ?? null;
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
            }, 100);
        }
    }

    /**
     * 注册Macroable的宏方法处理器
     * 用于支持Macroable特性的类
     * 
     * @return void
     */
    private function registerMacroableHandler(): void
    {
        if (method_exists($this, 'hasMacro') && property_exists(static::class, 'macros')) {
            self::registerMagicMethodHandler('__call', function ($method, $parameters) {
                if (static::hasMacro($method)) {
                    if (static::$macros[$method] instanceof Closure) {
                        return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
                    }
                    return call_user_func_array(static::$macros[$method], $parameters);
                }
                return static::$MAGIC_METHOD_CONTINUE;
            }, 100);
        }
    }

    /**
     * 注册AutoRegisterSingleton的处理器
     * 用于支持AutoRegisterSingleton特性的类
     * 
     * @return void
     */
    private function registerAutoRegisterSingletonHandler(): void
    {
        if (method_exists($this, 'registerSingleton')) {
            // 注册静态方法调用处理器
            self::registerMagicMethodHandler('__callStatic', function ($method, $parameters) {
                if (FrameTypeUtil::isLaravel() && method_exists('app', 'has') && app()->has(static::class)) {
                    $instance = app(static::class);
                    if (method_exists($instance, $method)) {
                        return $instance->$method(...$parameters);
                    }
                }
                return static::$MAGIC_METHOD_CONTINUE;
            }, 150);
        }
    }

    /**
     * 统一的__call魔术方法实现
     * 
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    public function __call($name, $arguments)
    {
        // 先检查是否是Laravel环境，且方法存在于app容器中
        if (FrameTypeUtil::isLaravel() && method_exists('app', 'has') && app()->has(static::class)) {
            $instance = app(static::class);
            if (method_exists($instance, $name)) {
                return $instance->$name(...$arguments);
            }
        }
        
        // 调用统一的魔术方法处理器
        return $this->handleMagicMethod('__call', [$name, $arguments]);
    }

    /**
     * 统一的__callStatic魔术方法实现
     * 
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed
     * @throws InternalServerErrorException
     */
    public static function __callStatic($name, $arguments)
    {
        // 先检查是否是Laravel环境
        if (FrameTypeUtil::isLaravel() && method_exists('app', 'has') && app()->has(static::class)) {
            $instance = app(static::class);
            if (method_exists($instance, $name)) {
                return $instance->$name(...$arguments);
            }
        }
        
        // 创建一个临时实例来处理静态调用
        $instance = new static();
        return $instance->handleMagicMethod('__callStatic', [$name, $arguments]);
    }

    /**
     * 统一的__get魔术方法实现
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
     * 统一的__set魔术方法实现
     * 
     * @param string $name 属性名
     * @param mixed $value 属性值
     * @return void
     * @throws InternalServerErrorException
     */
    public function __set($name, $value)
    {
        $this->handleMagicMethod('__set', [$name, $value]);
    }

    /**
     * 这个方法保持向后兼容性，实际处理已移至registerDefaultHandlers
     * 
     * @return void
     */
    public function initializeMagicMethodManager(): void
    {
        // 为了保持向后兼容性，此方法留空
        // 实际初始化逻辑已移至registerDefaultHandlers
    }
}