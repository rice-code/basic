<?php

namespace Rice\Basic\Support;


class Debug
{
    /** @var array 耗时统计 */
    public $takeTimeMap;

    /**
     * 设置时间点
     * @param $key
     * @param $value
     * @return $this
     */
    public function setTakeTime($key, $value)
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
}