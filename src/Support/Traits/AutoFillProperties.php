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
use Rice\Basic\Support\Annotation\ClassReflector;
use Rice\Basic\Components\Exception\InternalServerErrorException;

trait AutoFillProperties
{
    /**
     * @internal
     * @var array|mixed
     */
    private array $_params;
    /**
     * @internal
     * @var array
     */
    private array $_properties;
    /**
     * @internal
     * @var array
     */
    private array $_alias;
    /**
     * @internal
     * @var CacheContract|null
     */
    private ?CacheContract $_cache;

    /**
     * @throws ReflectionException
     * @throws InternalServerErrorException
     * @internal
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

        if (!is_object($params) && !is_array($params)) {
            new InternalServerErrorException(TypeEnum::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->_params      = $params;
        $annotation         = new ClassReflector($cache);
        $this->_properties  = $annotation->execute(get_class($this))->getClassProperties();
        $this->_alias       = $annotation->getAlias();
        $this->_cache       = $cache;

        $this->handle();
    }

    /**
     * @throws InternalServerErrorException
     * @internal
     */
    protected function handle(): void
    {
        $this->fill();
    }

    /**
     * @throws InternalServerErrorException
     * @internal
     */
    public function fill(): void
    {
        if (empty($this->_properties)) {
            return;
        }

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

    /**
     * 填充类属性值为类的值
     *
     * @internal
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
     * @internal
     * @param $name
     * @param array $values
     * @return void
     */
    public function fillArray($name, array $values): void
    {
        $this->{$name} = $values;
    }
}
