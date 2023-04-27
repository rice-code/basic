<?php

namespace Rice\Basic\Support\Properties;

use ReflectionProperty;
use ReflectionException;
use Rice\Basic\Components\Entity\FrameEntity;

class Properties
{
    private const VAR_PATTERN = '/.*@var\s+(\S+)/';
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
            // 排除包内部使用变量
            if (FrameEntity::inFilter($property->name)) {
                continue;
            }
            $type                    = $property->getType();
            // 未指定变量类型，匹配注释类型
            if (!$type) {
                $type = $this->matchVarDoc($property);
            }
            $newProperty             = new Property($type);
            $newProperty->name       = $property->getName();
            $newProperty->docComment = $property->getDocComment();

            $this->properties[$newProperty->name] = $newProperty;
        }

        return $this->properties ?? [];
    }

    /**
     * @param ReflectionProperty $property
     * @return string|null
     */
    public function matchVarDoc(ReflectionProperty $property): ?string
    {
        $matches = [];
        preg_match(self::VAR_PATTERN, $property->getDocComment(), $matches);

        if (!empty($matches[1])) {
            return $matches[1];
        }

        return null;
    }
}
