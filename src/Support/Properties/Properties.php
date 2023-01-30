<?php

namespace Rice\Basic\Support\Properties;

use ReflectionException;

class Properties
{
    protected \ReflectionClass $refectionClass;

    /**
     * @var Property[]
     */
    protected array $properties;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $namespace)
    {
        $this->refectionClass = new \ReflectionClass($namespace);
    }

    public function getProperties($filter = \ReflectionProperty::IS_PROTECTED): array
    {
        if (isset($this->properties)) {
            return $this->properties;
        }

        $properties = $this->refectionClass->getProperties($filter);

        foreach ($properties as $property) {
            $newProperty             = new Property($property->getType());
            $newProperty->name       = $property->getName();
            $newProperty->docComment = $property->getDocComment();

            $this->properties[] = $newProperty;
        }

        return $this->properties ?? [];
    }
}
