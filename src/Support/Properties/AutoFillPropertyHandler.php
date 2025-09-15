<?php

namespace Rice\Basic\Support\Properties;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Components\Enum\TypeEnum;
use Rice\Basic\Support\Utils\ExtractUtil;
use Rice\Basic\Support\Converts\TypeConvert;
use Rice\Basic\Support\Utils\FrameTypeUtil;
use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Support\Annotation\ClassReflector;
use Rice\Basic\Components\Exception\InternalServerErrorException;
use Rice\Basic\Components\Entity\FrameEntity;

/**
 * 属性自动填充处理器
 * 将AutoFillProperties trait的功能封装为独立的处理器类
 */
class AutoFillPropertyHandler
{
    /**
     * @var array|mixed
     */
    private array $_params;
    
    /**
     * @var array
     */
    private array $_properties;
    
    /**
     * @var array
     */
    private array $_alias;
    
    /**
     * @var CacheContract|null
     */
    private ?CacheContract $_cache;
    
    /**
     * @var bool 是否只填充当前类的属性（过滤父类属性）
     */
    private bool $_onlyCurrentClass = false;
    
    /**
     * @var object 使用属性填充的目标对象
     */
    private object $_target;
    
    /**
     * @var ReflectionClass 目标对象的反射类
     */
    private ReflectionClass $_reflection;
    
    /**
     * 构造函数
     * 
     * @param object $target 需要进行属性填充的目标对象
     * @throws ReflectionException
     */
    public function __construct(object $target)
    {
        $this->_target = $target;
        $this->_reflection = new ReflectionClass($target);
    }
    
    /**
     * 自动填充初始化方法
     * 
     * @param mixed $params 参数数据
     * @param CacheContract|null $cache 缓存实例
     * @param bool $onlyCurrentClass 是否只填充当前类的属性（过滤父类属性）。如果不提供，则保持当前设置
     * @throws InternalServerErrorException
     * @throws ReflectionException
     */
    public function initialize($params = null, CacheContract $cache = null, bool $onlyCurrentClass = null): void
    {
        // 如果没有提供参数，检查是否在Laravel环境中自动获取请求数据
        if (is_null($params) && FrameTypeUtil::isLaravel() && function_exists('app')) {
            try {
                $request = app('request');
                if ($request instanceof \Illuminate\Http\Request) {
                    $params = $request->all();
                }
            } catch (\Exception $e) {
                // 捕获可能的异常，确保代码继续执行
                $params = [];
            }
        }

        if (empty($params)) {
            return;
        }

        if (is_string($params)) {
            $params = json_decode($params, true);
        }

        if (!is_object($params) && !is_array($params)) {
            throw new InternalServerErrorException(TypeEnum::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->_params      = $params;
        $annotation         = new ClassReflector($cache);
        
        // 只有在显式提供了参数时才更新设置
        if ($onlyCurrentClass !== null) {
            $this->_onlyCurrentClass = $onlyCurrentClass;
            $annotation->setOnlyCurrentClass($onlyCurrentClass);
        }
        
        $this->_properties  = $annotation->execute(get_class($this->_target))->getClassProperties();
        $this->_alias       = $annotation->getAlias();
        $this->_cache       = $cache;

        $this->handle();
    }
    
    /**
     * 处理属性填充
     * 
     * @throws InternalServerErrorException
     */
    protected function handle(): void
    {
        $this->fill();
    }
    
    /**
     * 执行属性填充
     * 
     * @throws InternalServerErrorException
     */
    public function fill(): void
    {
        // 修复ExtractUtil::getCamelCase方法调用，确保传入正确的参数个数和空值检查
        $propertyArr = ExtractUtil::getCamelCase($this->_properties, get_class($this->_target), []);

        // 如果属性数组为空，尝试直接使用_params中的键作为属性名
        if (empty($propertyArr) && !empty($this->_params)) {
            foreach ($this->_params as $name => $value) {
                $this->setValue($name, $value);
            }
            return;
        }

        /**
         * @var Property $property
         */
        foreach ($propertyArr as $name => $property) {
            $loopIdx = StrUtil::snakeCaseToCamelCase($name);

            if (FrameEntity::inFilter($name)) {
                continue;
            }
            

            // 提取变量值
            $value = ExtractUtil::getValue($this->_params, $loopIdx);

            if (is_null($property)) {
                $this->setValue($name, $value);
                continue;
            }
            
            if ($property->isClass) {
                $this->fillClass($property, $name, $value);
                continue;
            }

            if ($property->isArray) {
                $this->fillArray($name, $value ?? []);
                continue;
            }

            // 强类型未设置值时，设置为null会报错
            if ($property->stronglyTyped && is_null($value)) {
                continue;
            }

            $this->setValue($name, $value);
        }
    }
    
    /**
     * 使用反射设置属性值
     * 
     * @param string $name 属性名
     * @param mixed $value 属性值
     */
    private function setValue(string $name, $value): void
    {
        try {
            // 尝试直接设置（对于public属性）
            $this->_target->{$name} = $value;
        } catch (\Error $e) {
            // 如果直接设置失败，使用反射设置（处理private和protected属性）
            try {
                if ($this->_reflection->hasProperty($name)) {
                    $property = $this->_reflection->getProperty($name);
                    $property->setAccessible(true);
                    $property->setValue($this->_target, $value);
                }
            } catch (\Exception $ex) {
                // 忽略无法设置的属性
            }
        }
    }
    
    /**
     * 填充类属性值为类的值
     * 
     * @param Property $property
     * @param string $name
     * @param mixed $values
     */
    public function fillClass(Property $property, string $name, $values): void
    {
        if (!isset($this->_properties[$property->namespace]) || is_null($values)) {
            $this->setValue($name, null);
            return;
        }

        if ($property->isArray) {
            $result = [];
            foreach ($values as $value) {
                $obj = new $property->namespace();
                if (method_exists($obj, 'autoFillInitialize')) {
                    $obj->autoFillInitialize($value, $this->_cache);
                }
                $result[] = $obj;
            }
            $this->setValue($name, $result);
            return;
        }

        $obj = new $property->namespace();
        if (method_exists($obj, 'autoFillInitialize')) {
            $obj->autoFillInitialize($values, $this->_cache);
        }
        $this->setValue($name, $obj);
    }
    
    /**
     * 填充类属性为数组的值
     * 
     * @param string $name
     * @param array $values
     */
    public function fillArray(string $name, array $values): void
    {
        $this->setValue($name, $values);
    }
    
    /**
     * 获取参数数据
     * 
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params ?? [];
    }
    
    /**
     * 获取属性配置
     * 
     * @return array
     */
    public function getProperties(): array
    {
        return $this->_properties ?? [];
    }
    
    /**
     * 获取别名配置
     * 
     * @return array
     */
    public function getAlias(): array
    {
        return $this->_alias ?? [];
    }
    
    /**
     * 获取缓存实例
     * 
     * @return CacheContract|null
     */
    public function getCache(): ?CacheContract
    {
        return $this->_cache;
    }
    
    /**
    /**
     * 设置是否只填充当前类的属性（过滤父类属性）
     */
    public function setOnlyCurrentClass(bool $onlyCurrentClass): self
    {
        $this->_onlyCurrentClass = $onlyCurrentClass;
        return $this;
    }
    
    /**
     * 获取是否只填充当前类的属性的设置
     */
    public function isOnlyCurrentClass(): bool
    {
        return $this->_onlyCurrentClass;
    }
}