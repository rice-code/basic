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
     * @var string
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
