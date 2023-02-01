<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Enum\NameTypeEnum;
use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Exception\DTOException;
use Rice\Basic\Exception\BaseException;
use Rice\Basic\Exception\SupportException;

trait Accessor
{
    /**
     * 默认开启 setter
     * @var bool
     */
    protected bool $_setter = true;

    /**
     * 默认开启 getter
     */
    protected bool $_getter = true;

    /**
     * @throws SupportException
     * @throws BaseException
     */
    public function __call($name, $args)
    {
        if (method_exists($this, 'resetAccessor')) {
            $this->resetAccessor();
        }
        $pattern = '/^([sg]et)(.*)/';

        if ($this->_getter && !$this->_setter) {
            $pattern = '/^(get)(.*)/';
        }

        if (!$this->_getter && $this->_setter) {
            $pattern = '/^(set)(.*)/';
        }

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

    private function setValue($attrName, $args): void
    {
        $this->{$attrName} = $args[0];
    }

    private function getValue($attrName)
    {
        return $this->{$attrName};
    }

    /**
     * @param object $obj
     * @param array  $fields
     * @param int    $nameType
     * @return array
     * @throws SupportException
     */
    private function assignElement(object $obj, array $fields, int $nameType): array
    {
        foreach (get_object_vars($obj) as $k => $v) {
            $key = $k;

            if (isset($key[0]) && '_' === $key[0]) {
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

            $val = $v;

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
     * @throws SupportException
     */
    public function toArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::UNLIMITED);
    }

    /**
     * @throws SupportException
     */
    public function toSnakeCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::SNAKE_CASE);
    }

    /**
     * @throws SupportException
     */
    public function toCamelCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::CAMEL_CASE);
    }
}
