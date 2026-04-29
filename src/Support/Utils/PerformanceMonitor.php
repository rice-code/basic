<?php

namespace Rice\Basic\Support\Utils;

/**
 * 性能监控工具类
 * 用于监控和分析代码执行性能，识别热点代码
 */
class PerformanceMonitor
{
    /**
     * @var array 性能数据存储
     */
    private static $performanceData = [];

    /**
     * @var array 计时器堆栈
     */
    private static $timers = [];

    /**
     * @var bool 是否启用性能监控
     */
    private static $enabled = true;

    /**
     * 启用性能监控.
     */
    public static function enable(): void
    {
        self::$enabled = true;
    }

    /**
     * 禁用性能监控.
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }

    /**
     * 启动计时器.
     *
     * @param string $name 计时器名称
     */
    public static function start(string $name): void
    {
        if (!self::$enabled) {
            return;
        }

        self::$timers[$name] = [
            'start_time'   => microtime(true),
            'start_memory' => memory_get_usage(),
        ];
    }

    /**
     * 停止计时器并记录性能数据.
     *
     * @param string $name     计时器名称
     * @param array  $metadata 附加元数据
     * @return array|null 性能数据，如果计时器不存在则返回null
     */
    public static function stop(string $name, array $metadata = []): ?array
    {
        if (!self::$enabled || !isset(self::$timers[$name])) {
            return null;
        }

        $timer     = self::$timers[$name];
        $endTime   = microtime(true);
        $endMemory = memory_get_usage();

        $duration   = ($endTime - $timer['start_time']) * 1000; // 转换为毫秒
        $memoryUsed = $endMemory - $timer['start_memory']; // 内存使用量（字节）

        // 记录性能数据
        $data = [
            'name'        => $name,
            'duration'    => $duration,
            'memory_used' => $memoryUsed,
            'metadata'    => $metadata,
            'timestamp'   => time(),
        ];

        if (!isset(self::$performanceData[$name])) {
            self::$performanceData[$name] = [];
        }

        self::$performanceData[$name][] = $data;

        // 从计时器堆栈中移除
        unset(self::$timers[$name]);

        return $data;
    }

    /**
     * 执行代码并测量性能.
     *
     * @param string   $name     操作名称
     * @param callable $callback 要执行的代码
     * @param array    $metadata 附加元数据
     * @return mixed 回调函数的返回值
     */
    public static function measure(string $name, callable $callback, array $metadata = [])
    {
        self::start($name);

        try {
            $result = $callback();
        } catch (\Exception $e) {
            self::stop($name, array_merge($metadata, ['exception' => $e->getMessage()]));

            throw $e;
        }

        self::stop($name, $metadata);

        return $result;
    }

    /**
     * 获取指定名称的性能数据.
     *
     * @param string|null $name 名称，为null时获取所有
     * @return array 性能数据
     */
    public static function getPerformanceData(string $name = null): array
    {
        if (null === $name) {
            return self::$performanceData;
        }

        return self::$performanceData[$name] ?? [];
    }

    /**
     * 清除性能数据.
     *
     * @param string|null $name 名称，为null时清除所有
     */
    public static function clearPerformanceData(string $name = null): void
    {
        if (null === $name) {
            self::$performanceData = [];
        } else {
            unset(self::$performanceData[$name]);
        }
    }

    /**
     * 获取性能统计信息.
     *
     * @param string|null $name 名称，为null时获取所有
     * @return array 统计信息
     */
    public static function getStats(string $name = null): array
    {
        $stats = [];
        $data  = self::getPerformanceData($name);

        if (null !== $name) {
            // 单个名称的统计
            if (!empty($data)) {
                $stats[$name] = self::calculateStatsForData($data);
            }
        } else {
            // 所有名称的统计
            foreach ($data as $key => $items) {
                $stats[$key] = self::calculateStatsForData($items);
            }
        }

        return $stats;
    }

    /**
     * 计算数据集的统计信息.
     *
     * @param array $data 数据数组
     * @return array 统计信息
     */
    private static function calculateStatsForData(array $data): array
    {
        $count = count($data);
        if (0 === $count) {
            return [
                'count'        => 0,
                'avg_duration' => 0,
                'min_duration' => 0,
                'max_duration' => 0,
                'avg_memory'   => 0,
                'min_memory'   => 0,
                'max_memory'   => 0,
            ];
        }

        $durations    = array_column($data, 'duration');
        $memoryUsages = array_column($data, 'memory_used');

        return [
            'count'        => $count,
            'avg_duration' => array_sum($durations) / $count,
            'min_duration' => min($durations),
            'max_duration' => max($durations),
            'avg_memory'   => array_sum($memoryUsages) / $count,
            'min_memory'   => min($memoryUsages),
            'max_memory'   => max($memoryUsages),
        ];
    }

    /**
     * 获取热点代码（基于执行时间）.
     *
     * @param int $limit 返回的热点数量
     * @return array 热点代码列表
     */
    public static function getHotspots(int $limit = 10): array
    {
        $stats    = self::getStats();
        $hotspots = [];

        foreach ($stats as $name => $stat) {
            if ($stat['count'] > 0) {
                $hotspots[] = [
                    'name'           => $name,
                    'total_duration' => $stat['avg_duration'] * $stat['count'],
                    'avg_duration'   => $stat['avg_duration'],
                    'count'          => $stat['count'],
                ];
            }
        }

        // 按总执行时间排序
        usort($hotspots, function ($a, $b) {
            return $b['total_duration'] <=> $a['total_duration'];
        });

        // 返回指定数量的热点
        return array_slice($hotspots, 0, $limit);
    }

    /**
     * 导出性能数据为JSON.
     *
     * @param string|null $name 名称，为null时导出所有
     * @return string JSON字符串
     */
    public static function exportJson(string $name = null): string
    {
        $data = self::getPerformanceData($name);

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * 导出性能数据为CSV.
     *
     * @param string|null $name 名称，为null时导出所有
     * @return string CSV字符串
     */
    public static function exportCsv(string $name = null): string
    {
        $data   = self::getPerformanceData($name);
        $output = "name,duration,memory_used,timestamp\n";

        foreach ($data as $key => $items) {
            foreach ($items as $item) {
                $output .= sprintf(
                    '%s,%.4f,%d,%d\n',
                    $item['name'],
                    $item['duration'],
                    $item['memory_used'],
                    $item['timestamp']
                );
            }
        }

        return $output;
    }

    /**
     * 将性能数据保存到文件.
     *
     * @param string      $filename 文件名
     * @param string      $format   格式（json或csv）
     * @param string|null $name     名称，为null时保存所有
     * @return bool 操作结果
     */
    public static function saveToFile(string $filename, string $format = 'json', string $name = null): bool
    {
        if (!in_array($format, ['json', 'csv'], true)) {
            return false;
        }

        $content = 'json' === $format ? self::exportJson($name) : self::exportCsv($name);

        return false !== file_put_contents($filename, $content);
    }

    /**
     * 获取当前内存使用情况.
     *
     * @param bool $realUsage 是否返回实际内存使用量
     * @return array 内存使用信息
     */
    public static function getMemoryUsage(bool $realUsage = false): array
    {
        $usage     = memory_get_usage($realUsage);
        $peakUsage = memory_get_peak_usage($realUsage);

        return [
            'current'       => $usage,
            'peak'          => $peakUsage,
            'current_human' => self::formatBytes($usage),
            'peak_human'    => self::formatBytes($peakUsage),
        ];
    }

    /**
     * 格式化字节数为人类可读的格式.
     *
     * @param int $bytes 字节数
     * @return string 格式化后的字符串
     */
    private static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[(int)$pow];
    }
}
