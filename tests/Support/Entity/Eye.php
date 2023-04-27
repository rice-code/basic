<?php

namespace Tests\Support\Entity;

use Rice\Basic\Components\Entity\BaseEntity;
use Rice\Basic\Support\Traits\AutoFillProperties;

class Eye extends BaseEntity
{
    use AutoFillProperties;
    /**
     * @var string
     */
    protected $size;
}
