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
    private string $_idx;

    /**
     * @throws ReflectionException
     * @throws SupportException
     */
    public function __construct($params, CacheContract $cache = null, $idx = '')
    {
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
        $this->_idx         = $idx;

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
            $propertyName = StrUtil::snakeCaseToCamelCase($name);

            if (FrameEntity::inFilter($name)) {
                continue;
            }

            if ($this->_idx) {
                $loopIdx = "{$this->_idx}.{$propertyName}";
            } else {
                $loopIdx = $propertyName;
            }

            // 提取变量值
            $value = ExtractUtil::getValue($this->_params, $loopIdx);

            if (is_null($property)) {
                $this->{$name} = $value;

                continue;
            }
            if ($property->isClass) {
                if (!isset($this->_properties[$property->namespace])) {
                    $this->{$name} = null;
                } elseif ($property->isArray) {
                    foreach ($value as $k => $v) {
                        $this->{$name}[] = new $property->namespace($this->_params, $this->_cache, "{$loopIdx}.{$k}");
                    }
                } else {
                    $this->{$name} = new $property->namespace($this->_params, $this->_cache, $loopIdx);
                }
            } elseif ($property->isArray) {
                $this->{$name} = [];

                if (!is_null($value)) {
                    foreach ($value as $k => $v) {
                        $this->{$name}[] = $v;
                    }
                }
            } else {
                $this->{$name} = $value;
            }
        }
    }

    public function beforeFillHook(&$params): void
    {
    }

    public function afterFillHook(&$params): void
    {
    }
}
