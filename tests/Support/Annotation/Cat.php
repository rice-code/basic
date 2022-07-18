<?php


namespace Tests\Support\Annotation;

use Rice\Basic\Support\Traits\AutoFillTrait;
use Tests\Support\Entity\Speak;

class Cat
{
    use AutoFillTrait;

    /**
     * 眼睛
     * @return $this
     * @throws \Exception
     * @var string
     * @Param $class
     */
    public $eyes;

    /**
     * @var Eat
     */
    public $eat;

    /**
     * @var Speak
     */
    public $speak;

    /**
     * @var string[]
     */
    public $hair;
}