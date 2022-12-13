<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Enum\NameTypeEnum;
use Rice\Basic\Enum\ExceptionEnum;
use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Exception\DTOException;
use Rice\Basic\Exception\SupportException;

trait Accessor
{
    public function __call($name, $args)
    {
        preg_match('/^([sg]et)(.*)/', $name, $matchArr);

        $style    = $matchArr[1] ?? null;
        $attrName = $matchArr[2] ?? null;

        if (!is_null($attrName)) {
            $attrName = lcfirst($attrName);
        }

        if (!property_exists($this, $attrName)) {
            throw new SupportException(ExceptionEnum::ATTR_NOT_DEFINE);
        }

        switch ($style) {
            case 'set':
                $this->setValue($attrName, $args);

                return $this;
            case 'get':
                return $this->getValue($attrName);
        }

        throw new DTOException(DTOException::METHOD_NOT_DEFINE);
    }

    private function setValue($attrName, $args)
    {
        $this->{$attrName} = $args[0];
    }

    private function getValue($attrName)
    {
        return $this->{$attrName};
    }

    /**
     * @param object $obj
     * @param array $fields
     * @param int $nameType
     * @return array
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

    public function toArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::UNLIMITED);
    }

    public function toSnakeCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::SNAKE_CASE);
    }

    public function toCamelCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::CAMEL_CASE);
    }
}
