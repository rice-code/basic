<?php


namespace Tests\Support\Annotation;


use Tests\Support\Speak;

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
}