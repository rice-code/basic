<?php

namespace Rice\Basic\Support\Properties;

use ReflectionException;
use Rice\Basic\Components\Entity\FrameEntity;

class Methods
{
    protected \ReflectionClass $refectionClass;

    /**
     * @var Method[]
     */
    protected array $methods;
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

    /**
     * @throws ReflectionException
     */
    public function getMethods($filter = \ReflectionMethod::IS_PUBLIC): array
    {
        if (isset($this->methods)) {
            return $this->methods;
        }

        $methods = $this->refectionClass->getMethods();
        foreach ($methods as $method) {
            $newMethod = new Method($method, $this->uses, $this->alias);
            $newMethod->getMethod($filter);
            if (array_key_exists('internal', $newMethod->docLabels)) {
                continue;
            }
            $this->methods[$this->refectionClass->getName().'@'.$method->getName()] = $newMethod;
        }

        return $this->methods ?? [];
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
