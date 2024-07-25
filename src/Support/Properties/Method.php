<?php

namespace Rice\Basic\Support\Properties;

class Method
{
    protected \ReflectionMethod $reflectionMethod;

    /**
     * @var array
     */
    protected array $uses;
    /**
     * @var array
     */
    protected array $alias;
    /**
     * 函数名.
     */
    public string $name;
    /**
     * 注释描述.
     */
    public string $docDesc;
    /**
     * 注释@相关值
     */
    public array $docLabels = [];

    public function __construct(\ReflectionMethod $reflectionMethod, $uses = [], $alias = [])
    {
        $this->reflectionMethod = $reflectionMethod;
        $this->uses             = $uses;
        $this->alias            = $alias;
    }

    public function getMethod($filter = \ReflectionProperty::IS_PROTECTED): self
    {
        if (isset($this->name)) {
            return $this;
        }

        $this->handleMethod();

        return $this;
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
     * @return void
     */
    public function handleMethod(): void
    {
        [$name, $comment, $labels] = DocComment::getMethodInfo($this->reflectionMethod);
        $this->name                = $name;
        $this->docLabels           = $labels;
        $this->docDesc             = $comment;
    }
}
