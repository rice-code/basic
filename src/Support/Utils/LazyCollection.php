<?php

namespace Rice\Basic\Support\Utils;

/**
 * 惰性加载集合类
 * 用于优化大型集合的处理，实现按需加载和处理元素.
 */
class LazyCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var callable 用于生成元素的回调函数
     */
    private $generator;

    /**
     * @var array 缓存已处理的结果
     */
    private $results = [];

    /**
     * @var bool 是否已完全迭代
     */
    private $fullyResolved = false;

    /**
     * @var bool 是否启用结果缓存
     */
    private $enableCache = false;

    /**
     * 构造函数.
     *
     * @param callable $generator 元素生成器函数
     */
    public function __construct(callable $generator)
    {
        $this->generator = $generator;
    }

    /**
     * 创建一个新的惰性集合.
     *
     * @param callable $generator 元素生成器函数
     * @return LazyCollection
     */
    public static function make(callable $generator): self
    {
        return new self($generator);
    }

    /**
     * 从数组创建惰性集合.
     *
     * @param array $items 数组元素
     * @return LazyCollection
     */
    public static function fromArray(array $items): self
    {
        // 优化：对于空数组，直接返回空集合
        if (empty($items)) {
            return new self(function() { return; });
        }
        
        return new self(function () use ($items) {
            foreach ($items as $key => $value) {
                yield $key => $value;
            }
        });
    }

    /**
     * 启用结果缓存，适用于需要多次迭代同一集合的场景
     *
     * @return LazyCollection
     */
    public function withCaching(): self
    {
        $this->enableCache = true;
        return $this;
    }

    /**
     * 禁用结果缓存（默认行为），适用于大型集合处理以减少内存占用
     *
     * @return LazyCollection
     */
    public function withoutCaching(): self
    {
        $this->enableCache = false;
        return $this;
    }

    /**
     * 获取迭代器.
     *
     * @return \Generator
     */
    public function getIterator(): \Generator
    {
        // 如果已经完全解析且启用了缓存，直接返回缓存的结果
        if ($this->fullyResolved && $this->enableCache) {
            foreach ($this->results as $key => $value) {
                yield $key => $value;
            }
            return;
        }

        // 清除之前的结果，防止内存泄漏
        if ($this->enableCache) {
            $this->results = [];
        }

        $generator = $this->generator;
        $generatorIterator = $generator();
        
        // 如果是Generator类型，直接使用
        if ($generatorIterator instanceof \Generator) {
            foreach ($generatorIterator as $key => $value) {
                if ($this->enableCache) {
                    $this->results[$key] = $value;
                }
                yield $key => $value;
            }
        } elseif (is_array($generatorIterator) || $generatorIterator instanceof \Traversable) {
            // 兼容其他可迭代类型
            foreach ($generatorIterator as $key => $value) {
                if ($this->enableCache) {
                    $this->results[$key] = $value;
                }
                yield $key => $value;
            }
        }

        $this->fullyResolved = true;
    }

    /**
     * 映射集合中的每个元素.
     *
     * @param callable $callback 映射回调函数
     * @return LazyCollection
     */
    public function map(callable $callback): self
    {
        $collection = $this;
        return new self(function () use ($callback, $collection) {
            foreach ($collection as $key => $value) {
                yield $key => $callback($value, $key);
            }
        });
    }

    /**
     * 过滤集合中的元素.
     *
     * @param callable $callback 过滤回调函数
     * @return LazyCollection
     */
    public function filter(callable $callback): self
    {
        $collection = $this;
        return new self(function () use ($callback, $collection) {
            foreach ($collection as $key => $value) {
                if ($callback($value, $key)) {
                    yield $key => $value;
                }
            }
        });
    }

    /**
     * 获取集合中的第一个元素.
     *
     * @return mixed|null
     */
    public function first()
    {
        foreach ($this as $value) {
            return $value;
        }

        return null;
    }

    /**
     * 获取集合中的前N个元素
     *
     * @param int $count 元素数量
     * @return LazyCollection
     */
    public function take(int $count): self
    {
        $collection = $this;
        return new self(function () use ($count, $collection) {
            $taken = 0;
            foreach ($collection as $key => $value) {
                if ($taken >= $count) {
                    break;
                }
                yield $key => $value;
                $taken++;
            }
        });
    }

    /**
     * 将惰性集合转换为普通数组.
     *
     * @return array
     */
    public function toArray(): array
    {
        if ($this->fullyResolved && $this->enableCache) {
            return $this->results;
        }

        $results = [];
        foreach ($this as $key => $value) {
            $results[$key] = $value;
        }

        return $results;
    }

    /**
     * 计算集合中元素的数量.
     *
     * @return int
     */
    public function count(): int
    {
        if ($this->fullyResolved && $this->enableCache) {
            return count($this->results);
        }

        $count = 0;
        foreach ($this as $item) {
            ++$count;
        }

        return $count;
    }

    /**
     * 检查集合是否已解析.
     *
     * @return bool
     */
    public function isFullyResolved(): bool
    {
        return $this->fullyResolved;
    }

    /**
     * 释放内部缓存的结果，释放内存
     */
    public function clearCache(): void
    {
        $this->results = [];
        $this->fullyResolved = false;
    }
}
