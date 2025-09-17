<?php


// 引入自动加载
require_once __DIR__ . '/../vendor/autoload.php';

use Rice\Basic\Support\Utils\ObjectPool;
use Rice\Basic\Support\Utils\LazyCollection;
use Rice\Basic\Support\Utils\HotspotAnalyzer;
use Rice\Basic\Support\Utils\PerformanceMonitor;

// 示例1：使用PerformanceMonitor测量代码块性能
function examplePerformanceMonitor()
{
    echo "\n====== 性能监控示例 ======\n";

    // 启动监控
    PerformanceMonitor::start('expensive_operation');

    // 模拟耗时操作
    $result = 0;
    for ($i = 0; $i < 1000000; ++$i) {
        $result += $i;
    }

    // 停止监控并获取性能数据
    $performanceData = PerformanceMonitor::stop('expensive_operation', [
        'operation_type' => 'calculation',
        'iterations'     => 1000000,
    ]);

    echo '耗时操作执行时间: ' . round($performanceData['duration'], 4) . " 毫秒\n";
    echo '内存使用: ' . round($performanceData['memory_used'] / 1024, 2) . " KB\n";

    // 使用measure方法简化性能测量
    $sum = PerformanceMonitor::measure('array_processing', function () {
        $array = range(1, 100000);

        return array_sum($array);
    });

    echo "数组处理结果: $sum\n";

    // 批量操作多次以收集统计数据
    for ($i = 0; $i < 5; ++$i) {
        PerformanceMonitor::measure('database_query_simulation', function () {
            // 模拟数据库查询
            usleep(rand(1000, 5000));

            return ['id' => 1, 'name' => 'test'];
        });
    }

    // 获取统计信息
    $stats = PerformanceMonitor::getStats('database_query_simulation');
    echo "\n数据库查询模拟统计:\n";
    echo '调用次数: ' . $stats['database_query_simulation']['count'] . "\n";
    echo '平均执行时间: ' . round($stats['database_query_simulation']['avg_duration'], 4) . " 毫秒\n";
    echo '最大执行时间: ' . round($stats['database_query_simulation']['max_duration'], 4) . " 毫秒\n";

    // 导出性能数据
    $jsonData = PerformanceMonitor::exportJson();
    file_put_contents(__DIR__ . '/performance_data.json', $jsonData);
    echo "\n性能数据已导出到 performance_data.json\n";
}

// 示例2：使用HotspotAnalyzer分析热点代码
function exampleHotspotAnalyzer()
{
    echo "\n====== 热点代码分析示例 ======\n";

    // 设置热点阈值
    HotspotAnalyzer::setCallCountThreshold(10);
    HotspotAnalyzer::setExecutionTimeThreshold(5);

    // 模拟不同方法的调用
    for ($i = 0; $i < 20; ++$i) {
        // 频繁调用的方法
        $startTime = microtime(true);
        frequentMethod($i);
        $executionTime = (microtime(true) - $startTime) * 1000;
        HotspotAnalyzer::recordMethodCall('ExampleClass', 'frequentMethod', $executionTime, ['param' => $i]);
    }

    for ($i = 0; $i < 5; ++$i) {
        // 耗时方法
        $startTime = microtime(true);
        timeConsumingMethod();
        $executionTime = (microtime(true) - $startTime) * 1000;
        HotspotAnalyzer::recordMethodCall('ExampleClass', 'timeConsumingMethod', $executionTime, []);
    }

    // 普通方法
    $startTime = microtime(true);
    normalMethod();
    $executionTime = (microtime(true) - $startTime) * 1000;
    HotspotAnalyzer::recordMethodCall('ExampleClass', 'normalMethod', $executionTime, []);

    // 获取热点代码列表
    $hotspots = HotspotAnalyzer::getHotspots();
    echo "\n热点代码列表:\n";
    foreach ($hotspots as $index => $hotspot) {
        echo ($index + 1) . '. ' . $hotspot['method'] . "\n";
        echo '   调用次数: ' . $hotspot['count'] . "\n";
        echo '   总执行时间: ' . round($hotspot['total_time'], 4) . " 毫秒\n";
        echo '   平均执行时间: ' . round($hotspot['avg_time'], 4) . " 毫秒\n";
    }

    // 获取性能建议
    if (!empty($hotspots)) {
        $firstHotspot    = $hotspots[0]['method'];
        $recommendations = HotspotAnalyzer::getPerformanceRecommendations($firstHotspot);

        echo "\n性能优化建议 (" . $firstHotspot . "):\n";
        foreach ($recommendations as $rec) {
            echo '[' . strtoupper($rec['severity']) . '] ' . $rec['message'] . "\n";
        }
    }

    // 获取分类统计
    $categoryStats = HotspotAnalyzer::getCategoryStats();
    echo "\n分类统计:\n";
    foreach ($categoryStats as $category => $stats) {
        echo "分类: $category\n";
        echo '   方法数量: ' . $stats['method_count'] . "\n";
        echo '   调用次数: ' . $stats['count'] . "\n";
        echo '   总执行时间: ' . round($stats['total_time'], 4) . " 毫秒\n";
    }

    // 导出热点分析数据
    $jsonData = HotspotAnalyzer::exportJson();
    file_put_contents(__DIR__ . '/hotspot_analysis.json', $jsonData);
    echo "\n热点分析数据已导出到 hotspot_analysis.json\n";
}

// 模拟频繁调用的方法
function frequentMethod($param)
{
    // 简单的计算操作
    return $param * $param;
}

// 模拟耗时方法
function timeConsumingMethod()
{
    // 模拟耗时操作
    usleep(20000); // 20毫秒
}

// 模拟普通方法
function normalMethod()
{
    // 简单操作
    $a = 1;
    $b = 2;

    return $a + $b;
}

// 示例3：综合性能优化示例
function examplePerformanceOptimization()
{
    echo "\n====== 性能优化综合示例 ======\n";

    // 1. 惰性加载大型集合
    echo "\n1. 惰性加载大型集合示例:\n";
    $startTime = microtime(true);

    // 创建大型惰性集合
    $lazyCollection = LazyCollection::make(function () {
        for ($i = 1; $i <= 1000000; ++$i) {
            yield $i;
        }
    });

    // 处理集合（只处理前10个元素）
    $result = $lazyCollection->filter(function ($value) {
        return 0 == $value % 2;
    })->map(function ($value) {
        return $value * 2;
    })->take(10)->toArray();

    $executionTime = (microtime(true) - $startTime) * 1000;

    echo '惰性集合处理前10个偶数并翻倍的结果: ' . implode(', ', $result) . "\n";
    echo '执行时间: ' . round($executionTime, 4) . " 毫秒\n";

    // 2. 使用对象池
    echo "\n2. 对象池示例:\n";

    // 创建对象池
    $objectPool = new ObjectPool(function () {
        // 创建示例对象
        return (object) ['id' => uniqid(), 'created_at' => time()];
    }, 10); // 池大小为10

    $startTime = microtime(true);

    // 从池中获取和归还对象
    $objects = [];
    for ($i = 0; $i < 100; ++$i) {
        $obj = $objectPool->get();
        // 使用对象
        $obj->value = $i;
        $objects[]  = $obj;

        // 归还部分对象
        if (0 == $i % 2) {
            $objectPool->return($objects[array_shift(array_keys($objects))]);
        }
    }

    // 归还剩余对象
    foreach ($objects as $obj) {
        $objectPool->return($obj);
    }

    $executionTime = (microtime(true) - $startTime) * 1000;

    echo "使用对象池处理100个对象操作\n";
    echo '执行时间: ' . round($executionTime, 4) . " 毫秒\n";
    echo '对象池统计 - 创建数量: ' . $objectPool->getCreatedCount() . ', 命中数量: ' . $objectPool->getHitCount() . "\n";

    // 3. 内存使用监控
    echo "\n3. 内存使用监控示例:\n";
    $memoryUsage = PerformanceMonitor::getMemoryUsage();
    echo '当前内存使用: ' . $memoryUsage['current_human'] . "\n";
    echo '峰值内存使用: ' . $memoryUsage['peak_human'] . "\n";

    // 模拟内存密集操作
    $largeArray = [];
    for ($i = 0; $i < 100000; ++$i) {
        $largeArray[] = ['id' => $i, 'data' => str_repeat('x', 100)];
    }

    $memoryUsageAfter = PerformanceMonitor::getMemoryUsage();
    echo '创建大型数组后内存使用: ' . $memoryUsageAfter['current_human'] . "\n";
    echo '内存增加: ' . round(($memoryUsageAfter['current'] - $memoryUsage['current']) / 1024 / 1024, 2) . " MB\n";

    // 释放内存
    unset($largeArray);

    $memoryUsageAfterRelease = PerformanceMonitor::getMemoryUsage();
    echo '释放内存后: ' . $memoryUsageAfterRelease['current_human'] . "\n";
}

// 运行所有示例
function runAllExamples()
{
    echo "性能监控和优化示例开始运行\n";
    echo "===========================\n";

    examplePerformanceMonitor();
    exampleHotspotAnalyzer();
    examplePerformanceOptimization();

    echo "\n===========================\n";
    echo "性能监控和优化示例运行结束\n";
}

// 启动示例
runAllExamples();

// 使用说明:
// 1. 运行此脚本: php PerformanceMonitoringExample.php
// 2. 查看控制台输出的性能数据和热点分析结果
// 3. 检查生成的JSON文件获取详细数据
// 4. 根据热点分析结果和性能建议进行代码优化
