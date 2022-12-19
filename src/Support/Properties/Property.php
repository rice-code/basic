<?php

namespace Rice\Basic\Support\Properties;

class Property
{
    /**
     * 属性类型.
     * @var string
     */
    public $type;

    /**
     * 属性名称.
     *
     * @var string
     */
    public $name;

    /**
     * 属性的注释.
     *
     * @var string
     */
    public $docComment;

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

    public function __construct(?string $type)
    {
        if (false !== strpos($type, '[]')) {
            $this->isArray = true;
            $name          = str_replace('[]', '', $type);
        }

        $this->type = $type;
    }
}
