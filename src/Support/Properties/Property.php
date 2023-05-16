<?php

namespace Rice\Basic\Support\Properties;

class Property
{
    /**
     * 属性类型.
     */
    public ?string $type;

    /**
     * 强类型
     *
     * @var bool $stronglyTyped
     */
    public bool $stronglyTyped = false;
    /**
     * 属性名称.
     */
    public string $name;

    /**
     * 属性的注释.
     */
    public string $docComment;

    /**
     * 属性命名空间.
     */
    public string $namespace;

    /**
     * 是否数组.
     */
    public bool $isArray = false;

    /**
     * 是否对象
     */
    public bool $isClass = false;

    public function __construct(?string $type)
    {
        $this->type = $type;

        if (false !== strpos($type, '[]')) {
            $this->isArray       = true;
            $this->type          = str_replace('[]', '', $type);
        }
    }
}
