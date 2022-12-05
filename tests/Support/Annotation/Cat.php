<?php

namespace Tests\Support\Annotation;

use Tests\Support\Entity\Speak as S;
use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * Class Cat
 * @package Tests\Support\Annotation
 *
 * @method self     setHair(string[] $value)
 */
class Cat
{
    use AutoFillProperties;
    use Accessor;

    /**
     * 眼睛.
     * @return $this
     * @throws \Exception
     * @var    string
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
