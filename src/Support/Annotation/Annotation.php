<?php

namespace Rice\Basic\Support\Annotation;

use ReflectionClass;
use ReflectionException;
use Rice\Basic\Support\FileNamespace;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Support\Utils\VerifyUtil;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Properties\Properties;

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
    /**
     * 已解析类，不再重复添加进队列.
     * @var array
     */
    private array $resolvedClass;

    /**
     * 获取对应权限属性.
     *
     * @var int
     */
    private int $filter = \ReflectionProperty::IS_PROTECTED;

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
            $this->buildClass($objClass);
            $this->analysisAttr();
        }

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

        if ($this->readCache($classFileName, $modifyTimestamp, $classNamespace, $aliasNamespace)) {
            return $this;
        }

        $this->parseFileForNamespace($classNamespace, $classFileName);

        $this->writeCache($classFileName, $modifyTimestamp, $classNamespace, $aliasNamespace);

        return $this;
    }

    /**
     * @param string $classNamespace
     * @param $classFileName
     * @return void
     */
    private function parseFileForNamespace(string $classNamespace, $classFileName): void
    {
        $this->uses  = FileNamespace::getInstance()->execute($classNamespace, $classFileName)->getUses();
        $this->alias = FileNamespace::getInstance()->getAlias();
    }

    public function readCache($classFileName, $modifyTimestamp, $classNamespace, $aliasNamespace): bool
    {
        if (VerifyUtil::notNull($this->cache)) {
            $modifyTime = $this->cache->get($modifyTimestamp);
            $content    = json_decode($this->cache->get($classNamespace), true);
            $alias      = json_decode($this->cache->get($aliasNamespace), true);
            if (VerifyUtil::notNullAndNotEmpty($content) && $modifyTime == filemtime($classFileName)) {
                $this->uses  = $content;
                $this->alias = $alias;

                return true;
            }
        }

        return false;
    }

    public function writeCache($classFileName, $modifyTimestamp, $classNamespace, $aliasNamespace): void
    {
        if (VerifyUtil::notNull($this->cache)) {
            $this->cache->set($modifyTimestamp, filemtime($classFileName));
            $this->cache->set($classNamespace, json_encode($this->uses, JSON_UNESCAPED_UNICODE));
            $this->cache->set($aliasNamespace, json_encode($this->alias, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @throws ReflectionException
     */
    public function analysisAttr(): void
    {
        $className                         = $this->class->getName();
        $properties                        = new Properties($className, $this->uses[$className], $this->alias[$className]);
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
        return $this->uses;
    }

    public function getAlias(): array
    {
        return $this->alias;
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
