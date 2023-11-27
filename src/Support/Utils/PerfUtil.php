<?php

namespace Rice\Basic\Support\Utils;

use Carbon\Carbon;

class PerfUtil
{
    /**
     * 秒.
     *
     * @param int $loop
     * @param $callback
     * @return int
     */
    public static function seconds(int $loop, $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i ) {
            $callback();
        }

        return Carbon::now()->diffInSeconds($start);
    }

    /**
     * 毫秒.
     *
     * @param int $loop
     * @param $callback
     * @return int
     */
    public static function milliseconds(int $loop, $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i ) {
            $callback();
        }

        return Carbon::now()->diffInMilliseconds($start);
    }

    /**
     * 微秒.
     *
     * @param int $loop
     * @param $callback
     * @return int
     */
    public static function microseconds(int $loop, $callback): int
    {
        $start = Carbon::now();
        for ($i = 0; $i < $loop; ++$i ) {
            $callback();
        }

        return Carbon::now()->diffInMicroseconds($start);
    }
}
