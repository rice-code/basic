<?php

namespace Rice\Basic\Support\Generate\Annotation;

class Property
{
    /**
     * 属性类型.
     * @var string
     */
    public $type;

    /**
     * 属性命名空间.
     * @var string
     */
    public $namespace;

    /**
     * 是否数组.
     * @var bool
     */
    public $isArray = false;

    /**
     * 是否对象
     * @var bool
     */
    public $isClass = false;

    public function __construct($name)
    {
        if (false !== strpos($name, '[]')) {
            $this->isArray = true;
            $name          = str_replace('[]', '', $name);
        }

        $this->type = $name;
    }
}
