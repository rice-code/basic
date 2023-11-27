<?php

namespace Rice\Basic\Support\Annotation;

use ReflectionClass;
use ReflectionException;
use Rice\Basic\Support\FileNamespace;
use Rice\Basic\Components\Enum\KeyEnum;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Properties\Properties;
use Rice\Basic\Components\Entity\AnnotationEntity;

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
    private array $classProperties = [];

    /**
     * 对象属性解析队列.
     * @var array
     */
    private array $queue;
    /**
     * 已解析类，不再重复添加进队列.
     * @var array
     */
    private array $resolvedClass;

    /**
     * 已解析类实体.
     *
     * @var AnnotationEntity
     */
    private AnnotationEntity $resolvedEntity;
    /**
     * 获取对应权限属性.
     *
     * @var int
     */
    private int $filter = \ReflectionProperty::IS_PROTECTED;

    public function __construct($cache = null)
    {
        $this->cache          = $cache;
        $this->resolvedEntity = AnnotationEntity::build($cache);
    }

    /**
     * @throws ReflectionException
     */
    public function execute($class): self
    {
        if (! $this->resolvedEntity->hasChangeFile()) {
            return $this;
        }

        $this->queue = $this->resolvedEntity->getChangeFiles();
        // 构建命名空间
        $this->queue[] = $class;
        while (!empty($this->queue)) {
            $objClass = array_shift($this->queue);
            $this->buildClass($objClass);
            $this->analysisAttr();
        }

        $this->writeCache();

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
        $classFileName   = $this->class->getFileName();

        $this->resolvedEntity->setMtime($classFileName, filemtime($classFileName));
        $this->parseFileForNamespace($classNamespace, $classFileName);

        return $this;
    }

    /**
     * @param string $classNamespace
     * @param $classFileName
     * @return void
     */
    private function parseFileForNamespace(string $classNamespace, $classFileName): void
    {
        $parse = FileNamespace::getInstance()->execute($classNamespace, $classFileName);
        $this->resolvedEntity->setUses($classNamespace, $parse->getUses()[$classNamespace]);
        $this->resolvedEntity->setAlias($classNamespace, $parse->getAlias()[$classNamespace]);
    }

    public function writeCache(): void
    {
        if ($this->cache) {
            $this->cache->set(
                KeyEnum::ANNOTATION_KEY,
                json_encode($this->resolvedEntity->toArray(), JSON_UNESCAPED_UNICODE)
            );
        }
    }

    /**
     * @throws ReflectionException
     */
    public function analysisAttr(): void
    {
        $className                         = $this->class->getName();
        $properties                        = new Properties(
            $className,
            $this->resolvedEntity->getUses($className),
            $this->resolvedEntity->getAlias($className)
        );
        $this->classProperties[$className] = $properties->getProperties($this->filter);
        foreach ($properties->getAllPropertyNamespaceName() as $namespaceName) {
            if (!isset($this->resolvedClass[$namespaceName])) {
                $this->queue[]                       = $namespaceName;
                $this->resolvedClass[$namespaceName] = true;
            }
        }
    }

    public function getFileName(): string
    {
        return $this->class->getFileName();
    }

    public function getUses(): array
    {
        return $this->resolvedEntity->getUses();
    }

    public function getAlias(): array
    {
        return $this->resolvedEntity->getAlias();
    }

    public function getClassProperties(): array
    {
        // 兼容类无 protected 变量问题
        return $this->classProperties;
    }

    public function getProperty($key): ?Property
    {
        return $this->classProperties[$this->class->getName()][$key] ?? null;
    }

    /**
     * @return int
     */
    public function getFilter(): int
    {
        return $this->filter;
    }

    /**
     * @param int $filter
     */
    public function setFilter(int $filter): void
    {
        $this->filter = $filter;
    }
}
