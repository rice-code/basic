<?php

namespace Rice\Basic\Support\Annotation\tags;

#[\Attribute]
class Doc
{
    protected string $text;
    protected string $var;

    public function __construct(string $var, string $text = '')
    {
        $this->var  = $var;
        $this->text = $text;
    }
}
