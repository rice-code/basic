<?php

namespace Rice\Basic\Support\Utils;

/**
 * 热点代码分析器
 * 用于识别系统中的热点代码，分析性能瓶颈.
 */
class HotspotAnalyzer
{
    /**
     * @var array 热点代码数据存储
     */
    private static $hotspotData = [];

    /**
     * @var array 方法调用计数
     */
    private static $methodCalls = [];

    /**
     * @var bool 是否启用热点分析
     */
    private static $enabled = true;

    /**
     * @var int 调用次数阈值
     */
    private static $callCountThreshold = 100;

    /**
     * @var float 执行时间阈值（毫秒）
     */
    private static $executionTimeThreshold = 100;

    /**
     * 启用热点分析.
     */
    public static function enable(): void
    {
        self::$enabled = true;
    }

    /**
     * 禁用热点分析.
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }

    /**
     * 设置调用次数阈值
     *
     * @param int $threshold 阈值
     */
    public static function setCallCountThreshold(int $threshold): void
    {
        self::$callCountThreshold = max(1, $threshold);
    }

    /**
     * 设置执行时间阈值
     *
     * @param float $threshold 阈值（毫秒）
     */
    public static function setExecutionTimeThreshold(float $threshold): void
    {
        self::$executionTimeThreshold = max(0.1, $threshold);
    }

    /**
     * 记录方法调用.
     *
     * @param string $className     类名
     * @param string $methodName    方法名
     * @param float  $executionTime 执行时间（毫秒）
     * @param array  $params        方法参数
     */
    public static function recordMethodCall(string $className, string $methodName, float $executionTime, array $params = []): void
    {
        if (!self::$enabled) {
            return;
        }

        $fullMethodName = $className . '::' . $methodName;

        // 增加调用计数
        if (!isset(self::$methodCalls[$fullMethodName])) {
            self::$methodCalls[$fullMethodName] = [
                'count'      => 0,
                'total_time' => 0,
                'min_time'   => INF,
                'max_time'   => 0,
                'params'     => [],
            ];
        }

        ++self::$methodCalls[$fullMethodName]['count'];
        self::$methodCalls[$fullMethodName]['total_time'] += $executionTime;
        self::$methodCalls[$fullMethodName]['min_time'] = min(self::$methodCalls[$fullMethodName]['min_time'], $executionTime);
        self::$methodCalls[$fullMethodName]['max_time'] = max(self::$methodCalls[$fullMethodName]['max_time'], $executionTime);

        // 记录参数统计信息
        self::recordParamsStats($fullMethodName, $params);

        // 检查是否达到热点标准
        if (self::isHotspot($fullMethodName)) {
            self::recordHotspot($fullMethodName, $executionTime, $params);
        }
    }

    /**
     * 记录参数统计信息.
     *
     * @param string $methodName 方法名
     * @param array  $params     参数数组
     */
    private static function recordParamsStats(string $methodName, array $params): void
    {
        // 简化参数，只记录类型和大小，不记录具体值以保护数据安全
        $paramTypes = [];
        foreach ($params as $key => $value) {
            $paramTypes[$key] = [
                'type' => gettype($value),
                'size' => is_array($value) || is_object($value) ? count((array) $value) : 0,
            ];
        }

        // 只保留最近的参数快照
        self::$methodCalls[$methodName]['params'] = $paramTypes;
    }

    /**
     * 检查方法是否是热点.
     *
     * @param string $methodName 方法名
     * @return bool 是否是热点
     */
    private static function isHotspot(string $methodName): bool
    {
        if (!isset(self::$methodCalls[$methodName])) {
            return false;
        }

        $stats   = self::$methodCalls[$methodName];
        $avgTime = $stats['total_time'] / $stats['count'];

        // 如果调用次数超过阈值，或者平均执行时间超过阈值，则视为热点
        return $stats['count'] >= self::$callCountThreshold || $avgTime >= self::$executionTimeThreshold;
    }

    /**
     * 记录热点信息.
     *
     * @param string $methodName    方法名
     * @param float  $executionTime 执行时间
     * @param array  $params        方法参数
     */
    private static function recordHotspot(string $methodName, float $executionTime, array $params): void
    {
        if (!isset(self::$hotspotData[$methodName])) {
            self::$hotspotData[$methodName] = [];
        }

        // 只保留最近的热点记录，避免内存占用过大
        if (count(self::$hotspotData[$methodName]) >= 100) {
            array_shift(self::$hotspotData[$methodName]);
        }

        self::$hotspotData[$methodName][] = [
            'timestamp'      => time(),
            'execution_time' => $executionTime,
            'call_count'     => self::$methodCalls[$methodName]['count'],
            'params_count'   => count($params),
        ];
    }

    /**
     * 获取方法调用统计信息.
     *
     * @param string|null $methodName 方法名，为null时获取所有
     * @return array 统计信息
     */
    public static function getMethodStats(string $methodName = null): array
    {
        if (null !== $methodName) {
            return self::$methodCalls[$methodName] ?? [];
        }

        return self::$methodCalls;
    }

    /**
     * 获取热点代码列表.
     *
     * @param int    $limit          返回的热点数量
     * @param string $orderBy        排序字段（total_time, count, avg_time）
     * @param string $orderDirection 排序方向（asc, desc）
     * @return array 热点代码列表
     */
    public static function getHotspots(int $limit = 20, string $orderBy = 'total_time', string $orderDirection = 'desc'): array
    {
        $hotspots = [];

        foreach (self::$methodCalls as $methodName => $stats) {
            if (self::isHotspot($methodName)) {
                $avgTime = $stats['total_time'] / $stats['count'];

                $hotspots[] = [
                    'method'     => $methodName,
                    'count'      => $stats['count'],
                    'total_time' => $stats['total_time'],
                    'avg_time'   => $avgTime,
                    'min_time'   => $stats['min_time'],
                    'max_time'   => $stats['max_time'],
                    'params'     => $stats['params'] ?? [],
                ];
            }
        }

        // 排序
        usort($hotspots, function ($a, $b) use ($orderBy, $orderDirection) {
            $direction = 'asc' === $orderDirection ? 1 : -1;

            switch ($orderBy) {
                case 'count':
                    return ($a['count'] - $b['count']) * $direction;
                case 'avg_time':
                    return ($a['avg_time'] - $b['avg_time']) * $direction;
                case 'total_time':
                default:
                    return ($a['total_time'] - $b['total_time']) * $direction;
            }
        });

        // 返回指定数量的热点
        return array_slice($hotspots, 0, $limit);
    }

    /**
     * 导出热点分析数据为JSON.
     *
     * @return string JSON字符串
     */
    public static function exportJson(): string
    {
        $data = [
            'hotspots'     => self::getHotspots(),
            'method_calls' => self::$methodCalls,
            'hotspot_data' => self::$hotspotData,
            'timestamp'    => time(),
            'thresholds'   => [
                'call_count'     => self::$callCountThreshold,
                'execution_time' => self::$executionTimeThreshold,
            ],
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * 重置所有数据.
     */
    public static function reset(): void
    {
        self::$hotspotData = [];
        self::$methodCalls = [];
    }

    /**
     * 获取热点代码分类统计
     *
     * @return array 分类统计信息
     */
    public static function getCategoryStats(): array
    {
        $categories = [];

        foreach (self::$methodCalls as $methodName => $stats) {
            // 从类名提取分类
            $className = explode('::', $methodName)[0];
            $category  = self::extractCategoryFromClassName($className);

            if (!isset($categories[$category])) {
                $categories[$category] = [
                    'count'      => 0,
                    'total_time' => 0,
                    'methods'    => [],
                ];
            }

            $categories[$category]['count']      += $stats['count'];
            $categories[$category]['total_time'] += $stats['total_time'];
            $categories[$category]['methods'][] = $methodName;
        }

        // 计算每个分类的平均执行时间
        foreach ($categories as $key => $category) {
            $categories[$key]['avg_time'] = $category['count'] > 0 ?
                $category['total_time'] / $category['count'] : 0;
            // 去重方法列表
            $categories[$key]['methods']      = array_unique($categories[$key]['methods']);
            $categories[$key]['method_count'] = count($categories[$key]['methods']);
        }

        return $categories;
    }

    /**
     * 从类名提取分类.
     *
     * @param string $className 类名
     * @return string 分类名称
     */
    private static function extractCategoryFromClassName(string $className): string
    {
        // 根据命名空间或类名特征提取分类
        $parts = explode('\\', $className);

        // 简单的分类逻辑，可以根据实际项目结构进行调整
        if (count($parts) >= 2) {
            // 取命名空间的第二个部分作为分类
            return $parts[1];
        }

        // 默认分类
        return 'Other';
    }

    /**
     * 获取性能建议.
     *
     * @param string $methodName 方法名
     * @return array 性能优化建议
     */
    public static function getPerformanceRecommendations(string $methodName): array
    {
        $recommendations = [];
        $stats           = self::getMethodStats($methodName);

        if (empty($stats)) {
            return $recommendations;
        }

        $avgTime = $stats['total_time'] / $stats['count'];

        // 根据不同情况提供建议
        if ($stats['count'] > self::$callCountThreshold * 2) {
            $recommendations[] = [
                'severity' => 'high',
                'message'  => '方法调用过于频繁，建议考虑缓存结果或优化调用逻辑',
                'method'   => $methodName,
                'metric'   => 'call_count',
                'value'    => $stats['count'],
            ];
        }

        if ($avgTime > self::$executionTimeThreshold * 2) {
            $recommendations[] = [
                'severity' => 'high',
                'message'  => '方法执行时间过长，建议优化算法或考虑异步处理',
                'method'   => $methodName,
                'metric'   => 'avg_time',
                'value'    => $avgTime,
            ];
        } elseif ($avgTime > self::$executionTimeThreshold) {
            $recommendations[] = [
                'severity' => 'medium',
                'message'  => '方法执行时间偏高，建议检查是否存在性能瓶颈',
                'method'   => $methodName,
                'metric'   => 'avg_time',
                'value'    => $avgTime,
            ];
        }

        // 检查参数大小
        if (isset($stats['params'])) {
            foreach ($stats['params'] as $paramName => $paramInfo) {
                if ($paramInfo['size'] > 1000) {
                    $recommendations[] = [
                        'severity' => 'medium',
                        'message'  => "参数 {$paramName} 大小过大，建议分批处理或优化数据结构",
                        'method'   => $methodName,
                        'metric'   => 'param_size',
                        'value'    => $paramInfo['size'],
                    ];
                }
            }
        }

        return $recommendations;
    }
}
