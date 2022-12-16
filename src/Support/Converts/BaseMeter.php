<?php

namespace Rice\Basic\Support\Converts;

use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Exception\SupportException;

abstract class BaseMeter
{
    /**
     * 数值
     * @var string
     */
    protected $num;

    /**
     * 锚点单位.
     * @var string
     */
    protected $anchorPointUnit = null;

    /**
     * 计算公式.
     * @var array
     */
    protected $calculates = [];

    /**
     * 单位.
     * @var string
     */
    protected $unit;

    public function __construct(string $num, string $unit, $scale = 4)
    {
        if (is_null($this->anchorPointUnit)) {
            throw new SupportException(BaseEnum::CLASS_PROPERTY_IS_NOT_OVERRIDDEN);
        }

        $this->num  = $num;
        $this->unit = $unit;
        $this->from($unit, $scale);
    }

    /**
     * 相加.
     * @param string $num
     * @param int    $scale
     * @return $this
     */
    public function add(string $num, $scale = 0): self
    {
        $this->num = bcadd($this->num, $num, $scale);

        return $this;
    }

    /**
     * @param string   $num
     * @param int|null $scale
     * @return $this
     */
    public function sub(string $num, ?int $scale = 0): self
    {
        $this->num = bcsub($this->num, $num, $scale);

        return $this;
    }

    /**
     * 乘法.
     * @param string $num
     * @param int    $scale
     * @return $this
     */
    public function mul(string $num, $scale = 0): self
    {
        $this->num = bcmul($this->num, $num, $scale);

        return $this;
    }

    /**
     * 除法.
     * @param string $num
     * @param int    $scale
     * @return $this
     * @throws SupportException
     */
    public function div(string $num, $scale = 0): self
    {
        if ('' === $num || '0' === $num) {
            throw new SupportException(SupportException::CANNOT_DIVIDE_BY_ZERO);
        }

        $this->num = bcdiv($this->num, $num, $scale);

        return $this;
    }

    /**
     * 取余.
     * @param string $num
     * @param int    $scale
     * @return $this
     */
    public function mod(string $num, $scale = 0): self
    {
        $this->num = bcmod($this->num, $num, $scale);

        return $this;
    }

    /**
     * 乘方.
     * @param string $exponent
     * @param int    $scale
     * @return $this
     */
    public function pow(string $exponent, $scale = 0): self
    {
        $this->num = bcpow($this->num, $exponent, $scale);

        return $this;
    }

    /**
     * 乘方再取余.
     * @param string $exponent
     * @param string $modulus
     * @param int    $scale
     * @return $this
     */
    public function powmod(string $exponent, string $modulus, int $scale = 0): self
    {
        $this->num = bcpowmod($this->num, $exponent, $modulus, $scale);

        return $this;
    }

    /**
     * 二次方根.
     * @param int $scale
     * @return $this
     */
    public function sqrt(int $scale = 0): self
    {
        $this->num = bcsqrt($this->num, $scale);

        return $this;
    }

    /**
     * 数字大小比较
     * 两个数相等时返回 0； $this->num 比 $num 大时返回 1； 其他则返回 -1。
     * @param string $num
     * @param int    $scale
     * @return int
     */
    public function comp(string $num, $scale = 0): int
    {
        return bccomp($this->num, $num, $scale);
    }

    /**
     * 大于.
     * @param string $num
     * @param int    $scale
     * @return bool
     */
    public function gt(string $num, $scale = 0): bool
    {
        return 1 === $this->comp($num, $scale);
    }

    /**
     * 小于.
     * @param string $num
     * @param int    $scale
     * @return bool
     */
    public function lt(string $num, $scale = 0): bool
    {
        return -1 === $this->comp($num, $scale);
    }

    /**
     * 等于.
     * @param string $num
     * @param int    $scale
     * @return bool
     */
    public function eq(string $num, $scale = 0): bool
    {
        return 0 === $this->comp($num, $scale);
    }

    /**
     * 全局设置小数位.
     * @param int $scale
     * @return $this
     */
    public function setScale(int $scale = 0): self
    {
        bcscale($scale);

        return $this;
    }

    /**
     * 获取值
     * @param int $scale
     * @return string
     */
    public function getNum($scale = 0): string
    {
        return number_format($this->num, $scale, '.', '');
    }

    /**
     * 转为设置单位.
     * @param $unit
     * @param int $scale
     * @return string
     */
    public function to($unit, $scale = 4): string
    {
        $handle = $this->calculates[$unit];
        if (is_callable($handle)) {
            return $handle($this->num, true);
        }

        return bcmul($this->num, $handle, $scale);
    }

    /**
     * 转为锚点单位.
     * @param $unit
     * @param int $scale
     * @return $this
     */
    protected function from($unit, $scale = 4): self
    {
        $handle = $this->calculates[$unit];
        if (is_callable($handle)) {
            $this->num = $handle($this->num, false);
        } else {
            $this->mul($handle, $scale);
        }

        return $this;
    }
}
