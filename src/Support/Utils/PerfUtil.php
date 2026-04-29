<?php

namespace Rice\Basic\Support\Utils;

use Carbon\Carbon;

class PerfUtil
{
    /**
     * 秒.
     *
     * @param int $loop
     * @param \Closure $callback
     * @return int
     */
    public static function seconds(int $loop, \Closure $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i) {
            $callback();
        }

        return Carbon::now()->diffInSeconds($start);
    }

    /**
     * 毫秒.
     *
     * @param int $loop
     * @param \Closure $callback
     * @return int
     */
    public static function milliseconds(int $loop, \Closure $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i) {
            $callback();
        }

        return Carbon::now()->diffInMilliseconds($start);
    }

    /**
     * 微秒.
     *
     * @param int $loop
     * @param     $callback
     *
     * @return int
     *
     * @psalm-param \Closure():void $callback
     */
    public static function microseconds(int $loop, \Closure $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i) {
            $callback();
        }

        return Carbon::now()->diffInMicroseconds($start);
    }
}
