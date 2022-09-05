<?php

namespace Rice\Basic\Support\converts;


class WeightConvert extends BaseMeter
{

    // 公制
    public const METRIC_T  = 't';          // 吨
    public const METRIC_KG = 'kg';         // 千克
    public const METRIC_G  = 'g';          // 克
    public const METRIC_MG = 'mg';         // 毫克

    // 常衡制
    public const BRITISH_ST  = 'st';        // 英石
    public const BRITISH_UKT = 'ukt';       // 英国长吨
    public const BRITISH_UST = 'ust';       // 美国短吨
    public const BRITISH_LB  = 'lb';        // 磅
    public const BRITISH_OZ  = 'oz';        // 盎司

    /**
     * @overwrite
     * @var string $anchorPointUnit
     */
    protected $anchorPointUnit = self::METRIC_KG;

    protected $calculates = [
        // 公制
        self::METRIC_T    => '1000',
        self::METRIC_KG => '1',
        self::METRIC_G  => '0.001',
        self::METRIC_MG => '0.000001',
        // 常衡制
        self::BRITISH_ST => '6.35029',
        self::BRITISH_UKT => '1016.047',
        self::BRITISH_UST => '907.1847',
        self::BRITISH_LB  => '0.453592',
        self::BRITISH_OZ  => '0.0283495',
    ];
}