<?php

namespace Rice\Basic\Support;


use Rice\Basic\Support\Traits\Accessor;

/**
 * @method self setPrintStartTxt($txt) 设置输出头文本
 * @method self setPrintEndTxt($txt) 设置输出尾文本
 *
 * Class Debug
 * @package Rice\Basic\Support
 */
class Debug
{
    use Accessor;

    public const BLOCK_PRINT_FMT = '#----------%s---------------#';

    private static $printStartTxt = 'start';

    private static $printEndTxt = 'end';

    /** @var array 耗时统计 */
    public $takeTimeMap;

    /**
     * 设置时间点
     * @param $key
     * @param $value
     * @return $this
     */
    public function setTakeTime($key, $value): self
    {
        $this->takeTimeMap[$key] = $value;
        return $this;
    }

    /**
     * 打印耗时
     * @param string $start
     * @param string $end
     * @return string
     */
    public function showTakeTime($start = 'start', $end = 'end'): string
    {
        // 单位秒
        $useTime = number_format($this->takeTimeMap[$end] - $this->takeTimeMap[$start], 6);
        var_dump("耗时：{$useTime} 秒");
        return $useTime;
    }

    /**
     * 块状打印
     * @param mixed ...$args
     */
    public static function blockPrint(...$args): void
    {
        var_dump(sprintf(self::BLOCK_PRINT_FMT, self::$printStartTxt));
        foreach ($args as $arg) {
            var_dump($arg);
        }
        var_dump(sprintf(self::BLOCK_PRINT_FMT, self::$printEndTxt));
    }
}