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
     * @var array
     */
    protected array $uses;
    /**
     * @var array
     */
    protected array $alias;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $namespace, $uses = [], $alias = [])
    {
        $this->refectionClass = new \ReflectionClass($namespace);
        $this->uses           = $uses;
        $this->alias          = $alias;
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
                $name,
                $constant->getValue(),
                $constant->getDocComment(),
            );

            $newProperty->namespace  = null;
            $this->properties[$name] = $newProperty;
        }

        return $this->properties ?? [];
    }

    /**
     * 根据类属性查询命名空间.
     *
     * @param Property $property
     * @return string
     */
    protected function findNamespace(Property $property): ?string
    {
        $propertyType = $property->type;

        if (array_key_exists($propertyType, $this->alias)) {
            $propertyType = $this->alias[$propertyType];
        }

        if (class_exists($namespace = $this->uses['this'] . '\\' . $propertyType)) {
            $property->isClass = true;

            return $namespace;
        }

        if (isset($this->uses[$propertyType]) && class_exists($namespace = $this->uses[$propertyType] . '\\' . $propertyType)) {
            $property->isClass = true;

            return $namespace;
        }

        return null;
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
                $name,
                '',
                $property->getDocComment(),
            );
            $newProperty->namespace  = $this->findNamespace($newProperty);
            $this->properties[$name] = $newProperty;
        }

        return $this->properties ?? [];
    }

    /**
     * 获取类名
     * example: A\B\Foo.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->refectionClass->getName();
    }

    /**
     * 获取类短名
     * example: Foo.
     *
     * @return string
     */
    public function getShortName(): string
    {
        return $this->refectionClass->getShortName();
    }

    /**
     * 获取命名空间名称
     * example: A\B.
     *
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->refectionClass->getNamespaceName();
    }

    /**
     * 获取所有属性的命名空间.
     *
     * @return array
     */
    public function getAllPropertyNamespaceName(): array
    {
        $namespaces = [];

        foreach ($this->getProperties() as $property) {
            if (empty($property->namespace)) {
                continue;
            }
            $namespaces[] = $property->namespace;
        }

        return array_unique($namespaces);
    }
}
