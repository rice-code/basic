<?php

namespace Rice\Basic\Support\Properties;

use ReflectionException;
use Rice\Basic\Components\Entity\FrameEntity;

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

        $constants  = $this->refectionClass->getReflectionConstants();
        $properties = $this->refectionClass->getProperties($filter);

        return array_merge(
            $this->handleConstants($constants),
            $this->handleProperties($properties)
        );
    }

    /**
     * @param array $constants
     * @return array|Property[]
     */
    public function handleConstants(array $constants): array
    {
        /**
         * @var \ReflectionClassConstant $constant
         */
        foreach ($constants as $constant) {
            // 排除包内部使用变量
            if (FrameEntity::inFilter($constant->name)) {
                continue;
            }
            $name        = $constant->getName();
            $newProperty = new Property(
                'const',
                $this->refectionClass->getName(),
                $this->refectionClass->getNamespaceName(),
                $name,
                $constant->getValue(),
                $constant->getDocComment(),
            );

            $this->properties[$name] = $newProperty;
        }

        return $this->properties ?? [];
    }

    /**
     * @param array $properties
     * @return array|Property[]
     */
    public function handleProperties(array $properties): array
    {
        foreach ($properties as $property) {
            // 排除包内部使用变量
            if (FrameEntity::inFilter($property->name)) {
                continue;
            }
            $property->setAccessible(true);
            $type        = $property->getType();
            $name        = $property->getName();
            $newProperty = new Property(
                ($type instanceof \ReflectionType) ? $type->getName() : null,
                $this->refectionClass->getName(),
                $this->refectionClass->getNamespaceName(),
                $name,
                '',
                $property->getDocComment(),
            );

            $this->properties[$name] = $newProperty;
        }

        return $this->properties ?? [];
    }
}
