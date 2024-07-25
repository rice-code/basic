<?php

namespace Rice\Basic\Support\Annotation;

use ReflectionClass;
use ReflectionException;
use Rice\Basic\Support\FileParser;
use Rice\Basic\Components\Enum\KeyEnum;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Support\Properties\Methods;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Properties\Properties;
use Rice\Basic\Components\Entity\AnnotationEntity;

class ClassReflector
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
    /**
     * 是否开启函数分析
     *
     * @var bool
     */
    private bool $enableMethods = false;

    public function isEnableMethods(): bool
    {
        return $this->enableMethods;
    }

    public function setEnableMethods(bool $enableMethods): void
    {
        $this->enableMethods = $enableMethods;
    }

    public function __construct($cache = null)
    {
        $this->cache          = $cache;
        $this->resolvedEntity = AnnotationEntity::build($cache);
    }

    /**
     * @throws ReflectionException
     */
    public function execute(string $class): self
    {
        if (!$this->resolvedEntity->hasChangeFile($class)) {
            return $this;
        }

        $this->queue = $this->resolvedEntity->getChangeFiles();
        // 构建命名空间
        $this->queue[] = $class;
        while (!empty($this->queue)) {
            $objClass = array_shift($this->queue);
            $this->buildClass($objClass);
            $this->analysisAttr();
            if ($this->enableMethods) {
                $this->analysisMethod();
            }
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
        $parse = FileParser::getInstance()->execute($classNamespace, $classFileName);
        $this->resolvedEntity->setUses($classNamespace, $parse->getUses()[$classNamespace]);
        $this->resolvedEntity->setAlias($classNamespace, $parse->getAlias()[$classNamespace]);
    }

    public function writeCache(): void
    {
        if ($this->cache) {
            $this->cache->set(
                KeyEnum::ANNOTATION_KEY,
                json_encode($this->resolvedEntity::getCaches(), JSON_UNESCAPED_UNICODE)
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
        $this->resolvedEntity::setClassProperties($className, $properties->getProperties($this->filter));
        foreach ($properties->getAllPropertyNamespaceName() as $namespaceName) {
            if (!isset($this->resolvedClass[$namespaceName])) {
                $this->queue[]                       = $namespaceName;
                $this->resolvedClass[$namespaceName] = true;
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    public function analysisMethod(): void
    {
        $className  = $this->class->getName();
        $methods = new Methods($className);
        $this->resolvedEntity::setClassMethods($className, $methods->getMethods());
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
        return $this->resolvedEntity::getClassProperties();
    }

    public function getProperty($key): ?Property
    {
        return $this->resolvedEntity::getClassProperties($this->class->getName(), $key);
    }

    public function getClassMethods($namespace = null, $key = null): array
    {
        // 兼容类无 protected 变量问题
        return $this->resolvedEntity::getClassMethods($namespace, $key);
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
