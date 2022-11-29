<?php

namespace Rice\Basic\Support\generate\Properties;

class Properties
{
    protected $refectionClass;

    /**
     * @var \ReflectionProperty[]
     */
    protected $properties;

    public function __construct(string $namespace)
    {
        $this->refectionClass = new \ReflectionClass($namespace);
    }

    public function getHeadComment()
    {
        return $this->refectionClass->getDocComment();
    }

    public function getProperties()
    {
        if (isset($this->properties)) {
            return $this->properties;
        }

        $properties = $this->refectionClass->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $this->properties[] = $property;
        }

        return $this->properties;
    }
}