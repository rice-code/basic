<?php

namespace Rice\Basic\Support\Annotation;

use ReflectionClass;
use Rice\Basic\Support\Contracts\AutoFillCacheContract;
use Rice\Basic\Support\Decide;
use Rice\Basic\Support\FileNamespace;

class Annotation
{
    /**
     * 缓存实体.
     * @var AutoFillCacheContract
     */
    private $cache;

    /**
     * 反射类.
     * @var ReflectionClass
     */
    private $class;

    /**
     * 属性映射数组.
     * @var array
     */
    private $propertyMap;

    /**
     * 命名空间映射数组.
     * @var array
     */
    private $fileNamespaceMap = [];

    /**
     * 对象属性解析队列.
     * @var array
     */
    private $queue;

    public function __construct($cache = null)
    {
        $this->cache = $cache;
    }

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
     * @throws \ReflectionException
     */
    public function buildClass($class): self
    {
        $this->class     = new ReflectionClass($class);
        $classNamespace  = $this->class->getName();
        $modifyTimestamp = $classNamespace . '_timestamp';
        $classFileName   = $this->class->getFileName();
        if (Decide::notNull($this->cache)) {
            $modifyTime = $this->cache->get($modifyTimestamp);
            $content    = json_decode($this->cache->get($classNamespace), true);
            if (Decide::notNullAndNotEmpty($content) && $modifyTime == filemtime($classFileName)) {
                $this->fileNamespaceMap = $content;

                return $this;
            }
        }
        $this->fileNamespaceMap = FileNamespace::getInstance()->matchNamespace($classNamespace, $classFileName)->getNamespaces();

        if (Decide::notNull($this->cache)) {
            $this->cache->set($modifyTimestamp, filemtime($classFileName));
            $this->cache->set($classNamespace, json_encode($this->fileNamespaceMap, JSON_UNESCAPED_UNICODE));
        }

        return $this;
    }

    public function analysisAttr(): void
    {
        $properties = $this->class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $pattern    = '/.*@var\s+(\S+)/';
        $className  = $this->class->getName();
        foreach ($properties as $property) {
            $matches = [];
            preg_match($pattern, $property->getDocComment(), $matches);
            if (isset($matches[1]) && !empty($matches[1])) {
                $docProperty                                    = (new Property($matches[1]));
                $this->propertyMap[$className][$property->name] = $docProperty;
                $docProperty->namespace                         = $this->selectNamespace($docProperty);

                continue;
            }

            $this->propertyMap[$className][$property->name] = null;
        }
    }

    /**
     * @param $property
     * @return string
     */
    public function selectNamespace(Property $property): string
    {
        $fileNamespaceMap = $this->fileNamespaceMap[$this->class->getName()];
        if (class_exists($namespace = $fileNamespaceMap['this'] . '\\' . $property->name)) {
            $property->isClass = true;
            $this->queue[]     = $namespace;

            return $namespace;
        }

        if (isset($fileNamespaceMap[$property->name]) && class_exists($namespace = $fileNamespaceMap[$property->name] . '\\' . $property->name)) {
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

    public function getNamespaceList(): array
    {
        return $this->fileNamespaceMap;
    }

    public function getProperty(): array
    {
        // 兼容类无 public 变量问题
        return $this->propertyMap ?? [];
    }
}
