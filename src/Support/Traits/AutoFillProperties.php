<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Exception\TypeException;
use Rice\Basic\Support\Annotation\Annotation;
use Rice\Basic\Support\Contracts\CacheContract;
use Rice\Basic\Support\Converts\TypeConvert;
use Rice\Basic\Support\DataExtract;
use Rice\Basic\Support\Generate\Annotation\Property;
use Rice\Basic\Support\Utils\StrUtil;

trait AutoFillProperties
{
    private $_params;
    private $_propertyArr;
    private $_alias;
    private $_cache;
    private $_idx;

    public function __construct($params, CacheContract $cache = null, $idx = '')
    {
        if (empty($params)) {
            return;
        }

        if (!is_object($params) || !is_array($params)) {
            new TypeException(TypeException::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->_params      = $params;
        $annotation         = (new Annotation($cache));
        $this->_propertyArr = $annotation->execute($this)->getProperty();
        $this->_alias       = $annotation->getAlias();
        $this->_cache       = $cache;
        $this->_idx         = $idx;

        $this->handle();
    }

    protected function handle(): void
    {
        $this->beforeFillHook($params);

        $this->fill();

        $this->afterFillHook($params);
    }

    public function fill(): void
    {
        $propertyArr = DataExtract::getCamelCase($this->_propertyArr, get_class($this));

        /**
         * @var Property $property
         */
        foreach ($propertyArr as $name => $property) {
            $propertyName = StrUtil::snakeCaseToCamelCase($name);

            if ($this->_idx) {
                $loopIdx = "{$this->_idx}.{$propertyName}";
            } else {
                $loopIdx = $propertyName;
            }

            $value = DataExtract::get($this->_params, $loopIdx);

            if (is_null($property)) {
                $this->{$name} = $value;

                continue;
            }

            if ($property->isClass) {
                if (!isset($this->_propertyArr[$property->namespace])) {
                    $this->{$name} = null;
                } elseif ($property->isArray) {
                    foreach ($value as $k => $v) {
                        $this->{$name}[] = new $property->namespace($this->_params, $this->_cache, "{$loopIdx}.{$k}");
                    }
                } else {
                    $this->{$name} = new $property->namespace($this->_params, $this->_cache, $loopIdx);
                }
            } elseif ($property->isArray) {
                foreach ($value as $k => $v) {
                    $this->{$name}[] = $v;
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
