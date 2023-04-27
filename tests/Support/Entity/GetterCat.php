<?php

namespace Tests\Support\Entity;

use Tests\Support\Entity\Speak as S;
use Rice\Basic\Support\Traits\Getter;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * Class Cat.
 * @method self     setEyes(array $value)
 * @method array   getEyes()
 * @method self     setEat(Eat $value)
 * @method Eat      getEat()
 * @method self     setSpeak(S $value)
 * @method S        getSpeak()
 * @method self     setHair(string[] $value)
 * @method string[] getHair()
 */
class GetterCat
{
    use AutoFillProperties;
    use Accessor;
    use Getter;

    /**
     * 眼睛.
     *
     * @return $this
     *
     * @throws \Exception
     *
     * @var []Eye
     * @Param $class
     */
    protected $eyes;

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
