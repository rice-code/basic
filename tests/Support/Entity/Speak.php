<?php

namespace Tests\Support\Entity;

use Rice\Basic\Support\Traits\Accessor;
use Rice\Basic\Support\Traits\AutoFillProperties;

class Speak
{
    use AutoFillProperties;
    use Accessor;

    protected $language;
}
