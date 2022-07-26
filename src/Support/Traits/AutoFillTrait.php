<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Exception\TypeException;
use Rice\Basic\Support\Annotation\Annotation;
use Rice\Basic\Support\Annotation\Property;
use Rice\Basic\Support\Contracts\AutoFillCacheContract;
use Rice\Basic\Support\Convert;
use Rice\Basic\Support\converts\TypeConvert;
use Rice\Basic\Support\DataExtract;
use Rice\Basic\Support\verify;

trait AutoFillTrait
{
    private $propertyArr;

    public function __construct($params, AutoFillCacheContract $cache = null)
    {
        if (!is_object($params) || !is_array($params)) {
            new TypeException(TypeException::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = TypeConvert::objToArr($params);
        }

        $this->propertyArr = (new Annotation($cache))->execute($this)->getProperty();

        if (!empty($params)) {
            $this->handle($params);
        }
    }

    protected function handle($params): void
    {
        $this->beforeFillHook($params);

        $this->fill($params, '');

        $this->afterFillHook($params);

    }

    public function fill($params, $idx): void
    {
        $propertyArr = DataExtract::getCamelCase($this->propertyArr, self::class);

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

            $value = $params[$loopIdx] ?? null;

            if (is_null($property)) {
                $this->{$name} = $value;
                continue;
            }

            if ($property->isClass) {
                if (!isset($this->propertyArr[$property->namespace])) {
                    $this->{$name} = null;
                } elseif ($property->isArray) {
                    foreach ($value as $k => $v) {
                        $this->{$name}[] = new $property->namespace($v, "{$loopIdx}.{$k}");
                    }
                } else {
                    $this->{$name} = new $property->namespace($value, $loopIdx);
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