<?php

namespace Rice\Basic\Support\Annotation;

use ReflectionClass;
use ReflectionException;
use Rice\Basic\Support\FileNamespace;
use Rice\Basic\Support\Utils\VerifyUtil;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Contracts\CacheContract;

class Annotation
{
    /**
     * 缓存实体.
     * @var CacheContract
     */
    private $cache;

    /**
     * 反射类.
     * @var ReflectionClass
     */
    private ReflectionClass $class;

    /**
     * 属性映射数组.
     * @var array
     */
    private array $properties = [];

    /**
     * 命名空间映射数组.
     * @var array
     */
    private array $uses = [];

    /**
     * 命名空间别名映射数组.
     * @var array
     */
    private array $alias = [];

    /**
     * 对象属性解析队列.
     * @var array
     */
    private array $queue;

    public function __construct($cache = null)
    {
        $this->cache = $cache;
    }

    /**
     * @throws ReflectionException
     */
    public function execute($class): self
    {
        // 构建命名空间
        $this->queue[] = $class;
        while (!empty($this->queue)) {
            $objClass = array_shift($this->queue);
            $this->buildClass($objClass)->analysisAttr();
        }

        // 赋值

        return $this;
    }

    /**
     * 构建反射类.
     * @param $class
     * @return $this
     * @throws ReflectionException
     */
    public function buildClass($class): self
    {
        $this->class     = new ReflectionClass($class);
        $classNamespace  = $this->class->getName();
        $modifyTimestamp = $classNamespace . '_timestamp';
        $aliasNamespace  = $classNamespace . '_alias';
        $classFileName   = $this->class->getFileName();
        if (VerifyUtil::notNull($this->cache)) {
            $modifyTime = $this->cache->get($modifyTimestamp);
            $content    = json_decode($this->cache->get($classNamespace), true);
            $alias      = json_decode($this->cache->get($aliasNamespace), true);
            if (VerifyUtil::notNullAndNotEmpty($content) && $modifyTime == filemtime($classFileName)) {
                $this->uses = $content;

                return $this;
            }
        }
        $this->uses  = FileNamespace::getInstance()->matchNamespace($classNamespace, $classFileName)->getUses();
        $this->alias = FileNamespace::getInstance()->getAlias();

        if (VerifyUtil::notNull($this->cache)) {
            $this->cache->set($modifyTimestamp, filemtime($classFileName));
            $this->cache->set($classNamespace, json_encode($this->uses, JSON_UNESCAPED_UNICODE));
            $this->cache->set($aliasNamespace, json_encode($this->alias, JSON_UNESCAPED_UNICODE));
        }

        return $this;
    }

    public function analysisAttr(): void
    {
        $properties = $this->class->getProperties(\ReflectionProperty::IS_PROTECTED);
        $pattern    = '/.*@var\s+(\S+)/';
        $className  = $this->class->getName();
        foreach ($properties as $property) {
            $matches = [];
            preg_match($pattern, $property->getDocComment(), $matches);
            if (isset($matches[1]) && !empty($matches[1])) {
                $docProperty                                   = (new Property($matches[1]));
                $this->properties[$className][$property->name] = $docProperty;
                $docProperty->namespace                        = $this->findNamespace($docProperty);

                continue;
            }

            $this->properties[$className][$property->name] = null;
        }
    }

    /**
     * 根据类属性查询命名空间
     * @param Property $property
     * @return string
     */
    public function findNamespace(Property $property): string
    {
        $className = $this->class->getName();
        $uses      = $this->uses[$className];
        $alias     = $this->alias[$className];

        $propertyType = $property->type;

        if (array_key_exists($propertyType, $alias)) {
            $propertyType = $alias[$propertyType];
        }

        if (class_exists($namespace = $uses['this'] . '\\' . $propertyType)) {
            $property->isClass = true;
            $this->queue[]     = $namespace;

            return $namespace;
        }

        if (isset($uses[$propertyType]) && class_exists($namespace = $uses[$propertyType] . '\\' . $propertyType)) {
            $property->isClass = true;
            $this->queue[]     = $namespace;

            return $namespace;
        }

        return '';
    }

    public function getFileName(): string
    {
        return $this->class->getFileName();
    }

    public function getUses(): array
    {
        return $this->uses;
    }

    public function getAlias(): array
    {
        return $this->alias;
    }

    public function getProperty(): array
    {
        // 兼容类无 protected 变量问题
        return $this->properties;
    }
}
