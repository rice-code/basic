<?php

namespace Rice\Basic\Support\Properties;

use Rice\Basic\Components\Entity\FrameEntity;

class Properties
{
    protected \ReflectionClass $reflectionClass;

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
     * @throws \ReflectionException
     */
    public function __construct(string $namespace, $uses = [], $alias = [])
    {
        $this->reflectionClass = new \ReflectionClass($namespace);
        $this->uses            = $uses;
        $this->alias           = $alias;
    }

    public function getProperties(int $filter = \ReflectionProperty::IS_PROTECTED, bool $onlyCurrentClass = false): array
    {
        if (isset($this->properties)) {
            return $this->properties;
        }

        $constants  = $this->reflectionClass->getReflectionConstants();
        $properties = $this->reflectionClass->getProperties($filter);

        // 确保这两个方法返回数组，避免array_merge参数为null
        $constantsResult  = $this->handleConstants($constants, $onlyCurrentClass)   ?? [];
        $propertiesResult = $this->handleProperties($properties, $onlyCurrentClass) ?? [];

        return array_merge(
            $constantsResult,
            $propertiesResult
        );
    }

    /**
     * @param array $constants
     * @param bool  $onlyCurrentClass 是否只获取当前类的常量
     * @return array|Property[]
     */
    public function handleConstants(array $constants, bool $onlyCurrentClass = false): array
    {
        /**
         * @var \ReflectionClassConstant $constant
         */
        foreach ($constants as $constant) {
            // 如果设置了只获取当前类的常量，检查常量是否定义在当前类中
            if ($onlyCurrentClass) {
                // 获取声明该常量的类
                $declaringClass = $constant->getDeclaringClass();
                // 如果常量不是定义在当前类中，则跳过
                if ($declaringClass->getName() !== $this->reflectionClass->getName()) {
                    continue;
                }
            }

            // 排除包内部使用变量
            if (FrameEntity::inFilter($constant->name)) {
                continue;
            }

            [$name, $value, $comment, $labels] = DocComment::getConstantInfo($constant);
            $newProperty                       = new Property(
                'const',
                $name,
                $value,
                $comment,
                false,
                $labels
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
        if (!is_null($propertyType)) {
            // 检查当前命名空间下的类
            if (class_exists($namespace = $this->uses['this'] . '\\' . $propertyType)) {
                $property->isClass = true;

                return $namespace;
            }

            // 检查use导入的命名空间下的类
            if (isset($this->uses[$propertyType]) && class_exists($namespace = $this->uses[$propertyType] . '\\' . $propertyType)) {
                $property->isClass = true;

                return $namespace;
            }

            // 直接检查类名（完整命名空间）
            if (class_exists($propertyType)) {
                $property->isClass = true;

                return $propertyType;
            }
        }

        return null;
    }

    /**
     * @param array $properties
     * @param bool  $onlyCurrentClass 是否只获取当前类的属性
     * @return array|Property[]
     */
    public function handleProperties(array $properties, bool $onlyCurrentClass = false): array
    {
        foreach ($properties as $property) {
            /*
             * @var \ReflectionProperty $property
             */
            $property->setAccessible(true);

            // 如果设置了只获取当前类的属性，检查属性是否定义在当前类中
            if ($onlyCurrentClass) {
                $declaringClass = $property->getDeclaringClass();
                // 如果属性不是定义在当前类中，则跳过
                if ($declaringClass->getName() !== $this->reflectionClass->getName()) {
                    continue;
                }
            }

            [$type, $name, $comment, $stronglyTyped, $labels] = DocComment::getPropertyInfo($property);

            // 存在内部注释标记的属性，不需要处理
            if (array_key_exists('internal', $labels)) {
                continue;
            }

            $newProperty  = new Property(
                $type,
                $name,
                '',
                $comment,
                $stronglyTyped,
                $labels
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
        return $this->reflectionClass->getName();
    }

    /**
     * 获取类短名
     * example: Foo.
     *
     * @return string
     */
    public function getShortName(): string
    {
        return $this->reflectionClass->getShortName();
    }

    /**
     * 获取命名空间名称
     * example: A\B.
     *
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->reflectionClass->getNamespaceName();
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
