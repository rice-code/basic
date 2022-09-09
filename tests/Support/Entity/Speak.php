<?php


namespace Tests\Support\Entity;


use Rice\Basic\Support\Traits\AutoFillProperties;

class Speak
{
    use AutoFillProperties;

    public $language;
}