<?php

namespace Rice\Basic\Support\Traits;

use ReflectionException;
use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Components\Enum\TypeEnum;
use Rice\Basic\Support\Utils\ExtractUtil;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Converts\TypeConvert;
use Rice\Basic\Components\Entity\FrameEntity;
use Rice\Basic\Support\Annotation\Annotation;
use Rice\Basic\Components\Exception\TypeException;
use Rice\Basic\Components\Exception\SupportException;

trait AutoFillProperties
{
    private array $_params;
    private array $_properties;
    private array $_alias;
    private ?CacheContract $_cache;

    /**
     * @throws ReflectionException
     * @throws SupportException
     */
    public function __construct($params, CacheContract $cache = null)
    {
        // 父类注册到容器中（若不符合条件不注册）
        if (method_exists($this, 'registerSingleton')) {
            $this->registerSingleton();
        }

        if (empty($params)) {
            return;
        }

        if (is_string($params)) {
            $params = json_decode($params, true);
        }

        if (!is_object($params) || !is_array($params)) {
            new TypeException(TypeEnum::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->_params      = $params;
        $annotation         = (new Annotation($cache));
        $this->_properties  = $annotation->execute($this)->getProperty();
        $this->_alias       = $annotation->getAlias();
        $this->_cache       = $cache;

        $this->handle();
    }

    /**
     * @throws SupportException
     */
    protected function handle(): void
    {
        $this->beforeFillHook($params);

        $this->fill();

        $this->afterFillHook($params);
    }

    /**
     * @throws SupportException
     */
    public function fill(): void
    {
        $propertyArr = ExtractUtil::getCamelCase($this->_properties, get_class($this));

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
                $this->{$name} = $value;

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

            $this->{$name} = $value;
        }
    }

    public function beforeFillHook(&$params): void
    {
    }

    public function afterFillHook(&$params): void
    {
    }

    /**
     * 填充类属性值为类的值
     *
     * @param Property $property
     * @param $name
     * @param $values
     * @return void
     */
    public function fillClass(Property $property, $name, $values): void
    {
        if (!isset($this->_properties[$property->namespace]) || is_null($values)) {
            $this->{$name} = null;

            return;
        }

        if ($property->isArray) {
            foreach ($values as $value) {
                $this->{$name}[] = new $property->namespace($value, $this->_cache);
            }

            return;
        }

        $this->{$name} = new $property->namespace($values, $this->_cache);
    }

    /**
     * 填充类属性为数组的值
     *
     * @param $name
     * @param array $values
     * @return void
     */
    public function fillArray($name, array $values): void
    {
        $this->{$name} = $values;
    }
}
