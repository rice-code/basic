<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Exception\DTOException;
use Rice\Basic\Lang;

trait Accessor
{
    public function __call($name, $args)
    {
        preg_match('/^([sg]et)(.*)/', $name, $matchArr);

        $style = $matchArr[1] ?? null;
        $attrName = $matchArr[2] ?? null;

        if (!is_null($attrName)) {
            $attrName = lcfirst($attrName);
        }

        if (!property_exists($this, $attrName)) {
            throw new DTOException(
                Lang::getInstance()
                    ->setFileName(DTOEnum::LANG_NAME)
                    ->setKey(DTOEnum::ATTR_NOT_DEFINE)->getMessage()
            );
        }

        switch ($style) {
            case 'set':
                $this->setValue($attrName, $args);
                return $this;
            case 'get':
                return $this->getValue($attrName);
        }

        throw new DTOException('this method not define');
    }

    private function setValue($attrName, $args)
    {
        $this->{$attrName} = $args[0];
    }

    private function getValue($attrName)
    {
        return $this->{$attrName};
    }

    public function toArray($fields = [])
    {
        $result = [];
        foreach (get_object_vars($this) as $k => $v) {
            if (empty($fields)) {
                $result[$k] = $v;
            } elseif (in_array($k, $fields)) {
                $result[$k] = $v;
            }
        }

        return $result;
    }
}