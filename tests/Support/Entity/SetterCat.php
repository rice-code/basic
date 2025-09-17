<?php

namespace Tests\Support\Entity;

use Tests\Support\Entity\Speak as S;
use Rice\Basic\Support\Traits\Setter;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * Class Cat.
 * @method self     setEyes(string $value)
 * @method string   getEyes()
 * @method self     setEat(Eat $value)
 * @method Eat      getEat()
 * @method self     setSpeak(S $value)
 * @method S        getSpeak()
 * @method self     setHair(string[] $value)
 * @method string[] getHair()
 */
class SetterCat
{
    use AutoFillProperties;
    use Accessor;
    use Setter;

    /**
     * 解决trait方法冲突，明确使用Setter的resetAccessor实现.
     */
    public function resetAccessor(): void
    {
        // 调用Setter trait中的实现
        $this->_setter = true;
        $this->_getter = false;
    }

    /**
     * @var Eye[]
     * @Param $class
     */
    public $eyes;

    /**
     * @var Eat
     */
    protected $eat;

    /**
     * @var S
     */
    protected $speak;

    /**
     * @var string[]
     */
    protected $hair;
}
