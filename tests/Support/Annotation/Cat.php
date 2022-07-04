<?php


namespace Tests\Support\Annotation;

use Tests\Support\Entity\Speak;

class Cat
{
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