<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Exception\TypeException;
use Rice\Basic\Support\Annotation\Annotation;
use Rice\Basic\Support\Annotation\Property;
use Rice\Basic\Support\Convert;
use Rice\Basic\Support\DataExtract;

trait AutoFillTrait
{
    private $propertyArr;

    public function __construct($params)
    {
        if (!is_object($params) || !is_array($params)) {
            new TypeException(TypeException::INVALID_TYPE);
        }

        if (is_object($params)) {
            $params = Convert::objToArr($params);
        }

        $this->propertyArr = (new Annotation())->execute($this)->getProperty();

        if (!empty($params)) {
            $this->handle($params);
        }
    }

    protected function handle($params)
    {
        $this->beforeFillHook($params);

        $this->fill($params, '');

        $this->afterFillHook($params);

    }

    public function fill($params, $idx): self
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

            var_dump('#----------赋值start---------------#');
            var_dump(self::class);
            var_dump($loopIdx);

            $value = $params[$loopIdx] ?? null;
            var_dump($value);
            var_dump('#----------赋值end---------------#');

            if (is_null($property)) {
                $this->{$name} = $value;
                continue;
            }

            if ($property->isClass) {
                if ($property->isArray) {
                    foreach ($value as $k => $v) {
                        var_dump($v);
                        $this->{$name}[] = new $property->namespace($v, "{$loopIdx}.{$k}");
                    }
                } else {
                    $this->{$name} = new $property->namespace($value, $loopIdx);
                }
            } elseif ($property->isArray) {
                foreach ($value as $k => $v) {
                    var_dump($v);
                    $this->{$name}[] = $v;
                }
            } else {
                $this->{$name} = $value;
            }
        }

        return $this;
    }

    public function beforeFillHook($params)
    {
    }

    public function afterFillHook($params)
    {
    }
}