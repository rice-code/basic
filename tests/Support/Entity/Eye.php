<?php

namespace Tests\Support\Entity;

use Rice\Basic\Domain\Entity\BaseEntity;
use Rice\Basic\Support\Traits\AutoFillProperties;

class Eye extends BaseEntity
{
    use AutoFillProperties;

    /**
     * @var string
     */
    protected $size;
}
