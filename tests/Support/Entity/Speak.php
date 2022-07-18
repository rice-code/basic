<?php


namespace Tests\Support\Entity;


use Rice\Basic\Support\Traits\AutoFillTrait;

class Speak
{
    use AutoFillTrait;

    public $language;
}