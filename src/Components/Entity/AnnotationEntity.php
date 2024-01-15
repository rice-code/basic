<?php

namespace Rice\Basic\Components\Entity;

use Rice\Basic\Components\Enum\KeyEnum;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Support\Traits\Singleton;

class AnnotationEntity extends BaseEntity
{
    use Singleton;
    private static array $caches = [
        KeyEnum::FILE_MTIME_KEY => [],
        KeyEnum::FILE_USE_KEY   => [],
        KeyEnum::FILE_ALIAS_KEY => [],
    ];

    private static array $classProperties = [];
    private static bool $checks           = false;

    public static function build(?CacheContract $cache): self
    {
        $entity = self::getInstance();
        if (empty(self::$caches[KeyEnum::FILE_USE_KEY])) {
            self::$caches = $cache ? $cache->get(KeyEnum::ANNOTATION_KEY, self::$caches) : self::$caches;
        }

        return $entity;
    }

    public function hasChangeFile(string $namespace): bool
    {
        // 当文件修改为空时，说明未进行缓存过任何文件
        if (empty(self::$classProperties[$namespace])) {
            return true;
        }

        // 已检查过，无需重复检查
        if (self::$caches) {
            return false;
        }

        foreach (self::$caches[KeyEnum::FILE_MTIME_KEY] ?? [] as $path => $time) {
            if (filemtime($path) !== (int) $time) {
                return true;
            }
        }

        return false;
    }

    public function getChangeFiles(): array
    {
        $changeFiles = [];
        foreach (self::$caches[KeyEnum::FILE_MTIME_KEY] as $path => $time) {
            if (filemtime($path) !== (int) $time) {
                $changeFiles[] = $path;
            }
        }

        // 标识为全部数据检查过
        self::$checks = true;

        return $changeFiles;
    }

    public static function setClassProperties(string $namespace, array $classProperties): void
    {
        self::$classProperties[$namespace] = $classProperties;
    }

    public static function getClassProperties($namespace = null, $key = null)
    {
        if ($namespace && $key) {
            return self::$classProperties[$namespace][$key] ?? null;
        }

        if ($namespace) {
            return self::$classProperties[$namespace] ?? null;
        }

        return self::$classProperties;
    }

    public static function getCaches(): array
    {
        return self::$caches;
    }

    public function setMtime($key, $value): self
    {
        self::$caches[KeyEnum::FILE_MTIME_KEY][$key] = $value;

        return $this;
    }

    public function delMtime($key): self
    {
        unset(self::$caches[KeyEnum::FILE_MTIME_KEY][$key]);

        return $this;
    }

    public function setUses($key, $value): self
    {
        self::$caches[KeyEnum::FILE_USE_KEY][$key] = $value;

        return $this;
    }

    public function getUses(string $className = null)
    {
        return $className ? self::$caches[KeyEnum::FILE_USE_KEY][$className] : self::$caches[KeyEnum::FILE_USE_KEY];
    }

    public function delUses($key): self
    {
        unset(self::$caches[KeyEnum::FILE_USE_KEY][$key]);

        return $this;
    }

    public function setAlias($key, $value): self
    {
        self::$caches[KeyEnum::FILE_ALIAS_KEY][$key] = $value;

        return $this;
    }

    public function getAlias(string $className = null)
    {
        return $className ? self::$caches[KeyEnum::FILE_ALIAS_KEY][$className] : self::$caches[KeyEnum::FILE_ALIAS_KEY];
    }

    public function delAlias($key): self
    {
        unset(self::$caches[KeyEnum::FILE_ALIAS_KEY][$key]);

        return $this;
    }
}
