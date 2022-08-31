<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Exception\TypeException;
use Rice\Basic\Support\Annotation\Annotation;
use Rice\Basic\Support\Annotation\Property;
use Rice\Basic\Support\Contracts\AutoFillCacheContract;
use Rice\Basic\Support\converts\TypeConvert;
use Rice\Basic\Support\DataExtract;
use Rice\Basic\Support\Debug;
use Rice\Basic\Support\verify;

trait AutoFillTrait
{
    private $_params;
    private $_propertyArr;
    private $_cache;

    public function __construct($params, $idx = '', AutoFillCacheContract $cache = null)
    {
        if (!is_object($params) || !is_array($params)) {
            new TypeException(TypeException::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->_params      = $params;
        $this->_propertyArr = (new Annotation($cache))->execute($this)->getProperty();
        $this->_cache       = $cache;

        if (!empty($this->_params)) {
            $this->handle($idx);
        }
    }

    protected function handle($idx): void
    {
        $this->beforeFillHook($params);

        $this->fill($idx);

        $this->afterFillHook($params);

    }

    public function fill($idx): void
    {
        $propertyArr = DataExtract::getCamelCase($this->_propertyArr, get_class($this));

        /**
         * @var $property Property
         */
        foreach ($propertyArr as $name => $property) {

            $propertyName = snake_case_to_camel_case($name);

            if ($idx) {
                $loopIdx = "{$idx}.{$propertyName}";
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
                        $this->{$name}[] = new $property->namespace($this->_params, "{$loopIdx}.{$k}", $this->_cache);
                    }
                } else {
                    $this->{$name} = new $property->namespace($this->_params, $loopIdx, $this->_cache);
                }
            } elseif ($property->isArray) {
                foreach ($value as $k => $v) {
                    verify::throwStrongType($property->name, $v);
                    $this->{$name}[] = $v;
                }
            } else {
                verify::throwStrongType($property->name, $value);
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