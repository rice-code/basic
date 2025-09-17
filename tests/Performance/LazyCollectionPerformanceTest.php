<?php

namespace Rice\Basic\Tests\Performance;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Utils\LazyCollection;

/**
 * LazyCollection性能测试
 * 用于比较优化前后LazyCollection的性能差异
 */
class LazyCollectionPerformanceTest extends TestCase
{
    /**
     * 测试不同大小数据集的性能
     * 
     * @dataProvider dataSetSizeProvider
     */
    public function testPerformanceWithDifferentDataSetSizes(int $size)
    {
        echo "\n测试数据集大小: $size\n";
        
        // 创建测试数据
        $data = range(1, $size);
        
        // 1. 测试标准数组处理性能
        $this->measureArrayPerformance($data);
        
        // 2. 测试LazyCollection性能（启用缓存）
        $this->measureLazyCollectionPerformance($data, true);
        
        // 3. 测试LazyCollection性能（默认禁用缓存）
        $this->measureLazyCollectionPerformance($data, false);
        
        // 释放内存
        unset($data);
    }
    
    /**
     * 数据集大小提供者
     */
    public function dataSetSizeProvider()
    {
        return [
            '小型数据集(100)' => [100],
            '中型数据集(10000)' => [10000],
            '大型数据集(100000)' => [100000],
            '超大型数据集(1000000)' => [1000000],
        ];
    }
    
    /**
     * 测量标准数组处理性能
     */
    private function measureArrayPerformance(array $data)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // 执行数组操作：过滤偶数并翻倍
        $result = array_map(
            function($value) { return $value * 2; },
            array_filter($data, function($value) { return $value % 2 == 0; })
        );
        
        // 只取前10个元素
        $result = array_slice($result, 0, 10);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000;
        $memoryUsed = $endMemory - $startMemory;
        
        echo "  标准数组处理: 执行时间 = " . round($executionTime, 4) . " ms, 内存使用 = " . round($memoryUsed / 1024, 2) . " KB\n";
        
        // 验证结果
        $this->assertCount(min(10, count($result)), $result);
        
        unset($result);
    }
    
    /**
     * 测量LazyCollection性能
     */
    private function measureLazyCollectionPerformance(array $data, bool $enableCaching)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // 创建LazyCollection并执行相同操作
        $collection = LazyCollection::fromArray($data);
        
        // 如果需要启用缓存
        if ($enableCaching) {
            $collection = $collection->withCaching();
        }
        
        // 执行操作：过滤偶数并翻倍，然后只取前10个元素
        $result = $collection
            ->filter(function($value) { return $value % 2 == 0; })
            ->map(function($value) { return $value * 2; })
            ->take(10)
            ->toArray();
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000;
        $memoryUsed = $endMemory - $startMemory;
        
        $mode = $enableCaching ? "启用缓存" : "禁用缓存(默认)";
        echo "  LazyCollection($mode): 执行时间 = " . round($executionTime, 4) . " ms, 内存使用 = " . round($memoryUsed / 1024, 2) . " KB\n";
        
        // 验证结果
        $this->assertCount(min(10, count($result)), $result);
        
        // 清理缓存（如果启用了缓存）
        if ($enableCaching && method_exists($collection, 'clearCache')) {
            $collection->clearCache();
        }
        
        unset($collection, $result);
    }
    
    /**
     * 测试在AutoFillPropertyHandler场景下的性能
     */
    public function testAutoFillScenarioPerformance()
    {
        $size = 10000; // 模拟中等大小的数据集
        echo "\n测试AutoFillPropertyHandler场景性能（数据集大小: " . $size . "）\n";
        
        // 创建模拟数据（类似于DTO对象集合）
        $data = array_map(function($i) {
            return ['id' => $i, 'name' => 'Item ' . $i, 'value' => $i * 10];
        }, range(1, $size));
        
        // 测量LazyCollection性能（启用缓存）
        $this->measureAutoFillScenario($data, true);
        
        // 测量LazyCollection性能（默认禁用缓存）
        $this->measureAutoFillScenario($data, false);
        
        unset($data);
    }
    
    /**
     * 测量AutoFillPropertyHandler场景的性能
     */
    private function measureAutoFillScenario(array $data, bool $enableCaching)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // 创建LazyCollection并执行类似于AutoFillPropertyHandler中的操作
        $collection = LazyCollection::fromArray($data);
        
        // 如果需要启用缓存
        if ($enableCaching) {
            $collection = $collection->withCaching();
        }
        
        // 模拟创建对象的操作
        $resultCollection = $collection->map(function($item) {
            // 模拟创建DTO对象并填充数据
            $obj = (object)[];
            $obj->id = $item['id'];
            $obj->name = $item['name'];
            $obj->value = $item['value'];
            return $obj;
        });
        
        // 模拟使用集合中的部分数据
        $first10Items = $resultCollection->take(10)->toArray();
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000;
        $memoryUsed = $endMemory - $startMemory;
        
        $mode = $enableCaching ? "启用缓存" : "禁用缓存(默认)";
        echo "  AutoFill场景($mode): 执行时间 = " . round($executionTime, 4) . " ms, 内存使用 = " . round($memoryUsed / 1024, 2) . " KB\n";
        
        // 验证结果
        $this->assertCount(min(10, count($first10Items)), $first10Items);
        
        // 清理缓存
        if ($enableCaching && method_exists($collection, 'clearCache')) {
            $collection->clearCache();
        }
        
        unset($collection, $resultCollection, $first10Items);
    }
    
    /**
     * 测试多次迭代的性能影响
     */
    public function testMultipleIterationsPerformance()
    {
        $size = 100000;
        echo "\n测试多次迭代性能影响（数据集大小: " . $size . "）\n";
        
        // 创建测试数据
        $data = range(1, $size);
        
        // 测试启用缓存的情况（适合多次迭代）
        $collectionWithCache = LazyCollection::fromArray($data);
        
        // 第一次迭代
        $startTime1 = microtime(true);
        $count1 = iterator_count($collectionWithCache->getIterator());
        $endTime1 = microtime(true);
        
        // 第二次迭代（应该更快，因为有缓存）
        $startTime2 = microtime(true);
        $count2 = iterator_count($collectionWithCache->getIterator());
        $endTime2 = microtime(true);
        
        echo "  启用缓存 - 第一次迭代: " . round(($endTime1 - $startTime1) * 1000, 4) . " ms\n";
        echo "  启用缓存 - 第二次迭代: " . round(($endTime2 - $startTime2) * 1000, 4) . " ms\n";
        echo "  性能提升: " . round((($endTime1 - $startTime1) / ($endTime2 - $startTime2)), 2) . "x\n";
        
        // 测试禁用缓存的情况
        $collectionWithoutCache = LazyCollection::fromArray($data)->withoutCaching();
        
        // 第一次迭代
        $startTime3 = microtime(true);
        $count3 = iterator_count($collectionWithoutCache->getIterator());
        $endTime3 = microtime(true);
        
        // 第二次迭代
        $startTime4 = microtime(true);
        $count4 = iterator_count($collectionWithoutCache->getIterator());
        $endTime4 = microtime(true);
        
        echo "  禁用缓存 - 第一次迭代: " . round(($endTime3 - $startTime3) * 1000, 4) . " ms\n";
        echo "  禁用缓存 - 第二次迭代: " . round(($endTime4 - $startTime4) * 1000, 4) . " ms\n";
        
        // 验证结果
        $this->assertEquals($count1, $count2);
        $this->assertEquals($count3, $count4);
        
        // 清理
        if (method_exists($collectionWithCache, 'clearCache')) {
            $collectionWithCache->clearCache();
        }
        
        if (method_exists($collectionWithoutCache, 'clearCache')) {
            $collectionWithoutCache->clearCache();
        }
        
        unset($collectionWithCache, $collectionWithoutCache, $data);
    }
}