<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Enum\NameTypeEnum;
use Rice\Basic\Exception\CommonException;
use Rice\Basic\Exception\DTOException;

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
            throw new DTOException(DTOException::ATTR_NOT_DEFINE);
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
     * @param array $fields
     * @param $filters
     * @param $nameType
     * @return array
     * @throws CommonException
     */
    private function assignElement(array $fields, array $filters, int $nameType): array
    {
        foreach (get_object_vars($this) as $k => $v) {
            $key = $k;
            switch ($nameType) {
                case NameTypeEnum::CAMEL_CASE:
                    $key = snake_case_to_camel_case($key);
                    break;
                case NameTypeEnum::SNAKE_CASE:
                    $key = camel_case_to_snake_case($key);
                    break;
            }

            if (in_array($key, $filters)) {
                continue;
            }

            if (empty($fields)) {
                $result[$key] = $v;
            } elseif (in_array($key, $fields)) {
                $result[$key] = $v;
            }
        }
        return $result;
    }

    public function toArray($fields = [], $filters = ['propertyArr'])
    {
        return $this->assignElement($fields, $filters, NameTypeEnum::UNLIMITED);
    }

    public function toSnakeCaseArray($fields = [], $filters = ['property_arr'])
    {
        return $this->assignElement($fields, $filters, NameTypeEnum::SNAKE_CASE);
    }

    public function toCamelCaseArray($fields = [], $filters = ['propertyArr'])
    {
        return $this->assignElement($fields, $filters, NameTypeEnum::CAMEL_CASE);
    }
}