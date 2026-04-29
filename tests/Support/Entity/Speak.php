<?php

namespace Tests\Support\Entity;

use Rice\Basic\Domain\Entity\BaseEntity;
use Rice\Basic\Support\Traits\AutoFillProperties;

/**
 * @method self     setLanguage(string $value)
 * @method string getLanguage()
 */
class Speak extends BaseEntity
{
    use AutoFillProperties;

    protected $language;
}
