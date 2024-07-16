<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Components\Enum\BaseEnum;
use Rice\Basic\Components\Enum\NameTypeEnum;
use Rice\Basic\Components\Entity\FrameEntity;
use Rice\Basic\Components\Exception\BaseException;
use Rice\Basic\Components\Exception\SupportException;

trait Accessor
{
    /**
     * 默认开启 setter.
     *
     * @internal
     * @var bool
     */
    protected bool $_setter = true;

    /**
     * 默认开启 getter.
     *
     * @internal
     */
    protected bool $_getter = true;

    /**
     * 默认属性对象只读.
     *
     * @internal
     * @var bool
     */
    protected bool $_readOnly = true;

    /**
     * @internal
     * @throws SupportException
     * @throws BaseException
     */
    public function __call($name, $args)
    {
        if (method_exists($this, 'resetAccessor')) {
            $this->resetAccessor();
        }

        $pattern = $this->getAuth();

        $matches = [];
        preg_match($pattern, $name, $matches);

        $style    = $matches[1] ?? null;
        $attrName = $matches[2] ?? null;

        if (is_null($style) && is_null($attrName)) {
            throw new SupportException(BaseEnum::METHOD_NOT_DEFINE);
        }

        $attrName = lcfirst($attrName);

        if (!property_exists($this, $attrName)) {
            throw new SupportException(BaseEnum::ATTR_NOT_DEFINE);
        }

        switch ($style) {
            case 'set':
                $this->setValue($attrName, $args);

                return $this;
            case 'get':
                return $this->getValue($attrName);
        }

        throw new SupportException(BaseEnum::METHOD_NOT_DEFINE);
    }

    /**
     * @internal
     * @return string
     */
    public function getAuth(): string
    {
        $pattern = '/^([sg]et)(.*)/';

        if ($this->_getter && !$this->_setter) {
            $pattern = '/^(get)(.*)/';
        }

        if (!$this->_getter && $this->_setter) {
            $pattern = '/^(set)(.*)/';
        }

        return $pattern;
    }

    /**
     * @internal
     * @param $attrName
     * @param $args
     * @return void
     */
    private function setValue($attrName, $args): void
    {
        $this->{$attrName} = $args[0];
    }

    /**
     * @internal
     * @param $attrName
     * @return mixed
     */
    private function getValue($attrName)
    {
        // 只读，因为对象 return 出去可以修改内部值，破坏封装性
        if ($this->_readOnly && is_object($this->{$attrName})) {
            return clone $this->{$attrName};
        }

        return $this->{$attrName};
    }

    /**
     * @internal
     * @param object $obj
     * @param array  $fields
     * @param int    $nameType
     * @return array
     */
    private function assignElement(object $obj, array $fields, int $nameType): array
    {
        $oReflectionClass = new \ReflectionClass($obj);
        foreach ($oReflectionClass->getProperties() as $property) {
            $key = $property->getName();

            // 过滤框架内部定义字段
            if (FrameEntity::inFilter($key)) {
                continue;
            }

            switch ($nameType) {
                case NameTypeEnum::CAMEL_CASE:
                    $key = StrUtil::snakeCaseToCamelCase($key);

                    break;
                case NameTypeEnum::SNAKE_CASE:
                    $key = StrUtil::camelCaseToSnakeCase($key);

                    break;
            }

            // 反射 private, protect 可见开启，保证能够获取属性值 （php8.1 默认开启）
            $property->setAccessible(true);
            $val = $property->getValue($obj);

            if (is_object($val)) {
                $val = $this->assignElement($val, $fields, $nameType);
            }

            if (is_array($val) && isset($val[0]) && is_object($val[0])) {
                $tempVal = [];
                foreach ($val as $item) {
                    $tempVal[] = $this->assignElement($item, $fields, $nameType);
                }
                $val = $tempVal;
            }

            if (empty($fields)) {
                $result[$key] = $val;
            } elseif (in_array($key, $fields, true)) {
                $result[$key] = $val;
            }
        }

        return $result ?? [];
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::UNLIMITED);
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toSnakeCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::SNAKE_CASE);
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toCamelCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::CAMEL_CASE);
    }
}
