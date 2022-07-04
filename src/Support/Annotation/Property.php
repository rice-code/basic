<?php


namespace Rice\Basic\Support\Annotation;


class Property
{
    /**
     * 属性类型
     * @var string
     */
    public $name;

    /**
     * 属性命名空间
     * @var string
     */
    public $namespace;

    /**
     * 是否数组
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
        if (strpos($name, '[]') !== false) {
            $this->isArray = true;
            $name = str_replace('[]', '', $name);
        }

        $this->name = $name;
    }
}