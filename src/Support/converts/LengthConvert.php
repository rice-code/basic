<?php

namespace Rice\Basic\Support\converts;

class LengthConvert extends BaseMeter
{
    // 公制
    public const METRIC_KM = 'km';  // 千米
    public const METRIC_M  = 'm';   // 米
    public const METRIC_DM = 'dm';  // 分米
    public const METRIC_CM = 'cm';  // 厘米
    public const METRIC_MM = 'mm';  // 毫米

    // 英制
    public const BRITISH_IN = 'in'; // 英寸
    public const BRITISH_FT = 'ft'; // 英尺
    public const BRITISH_YD = 'yd'; // 码
    public const BRITISH_MI = 'mi'; // 英里

    /**
     * @overwrite
     * @var string
     */
    protected $anchorPointUnit = self::METRIC_M;

    protected $calculates = [
        // 公制
        self::METRIC_KM  => '1000',
        self::METRIC_M   => '1',
        self::METRIC_DM  => '0.1',
        self::METRIC_CM  => '0.01',
        self::METRIC_MM  => '0.001',
        // 英制
        self::BRITISH_IN => '0.0254',
        self::BRITISH_FT => '0.3048',
        self::BRITISH_YD => '0.9144',
        self::BRITISH_MI => '1609.344',
    ];
}
