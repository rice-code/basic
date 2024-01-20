<?php

namespace Rice\Basic\Support\Properties;

class Property
{
    public const LABEL_PATTERN = '/.*@(\S+)[ ]+([^\t\n\r]+)/';
    /**
     * 属性类型.
     */
    public ?string $type;

    /**
     * 强类型.
     *
     * @var bool
     */
    public bool $stronglyTyped = false;
    /**
     * 属性名称.
     */
    public string $name;
    /**
     * 属性名称.
     */
    public $value;
    /**
     * 注释描述.
     */
    public string $docDesc;
    /**
     * 注释@相关值
     */
    public array $docLabels = [];

    /**
     * 该属性若是对象的话，必然会存在一个命名空间.
     */
    public ?string $namespace = null;

    /**
     * 是否数组.
     */
    public bool $isArray = false;

    /**
     * 是否对象
     */
    public bool $isClass = false;

    public function __construct(?string $type, $name = '', $value = null, $comment = '', $stronglyTyped = false, $docLabels = [])
    {
        $this->type           = $type;
        $this->name           = $name;
        $this->value          = $value;
        $this->docDesc        = $comment;
        $this->stronglyTyped  = $stronglyTyped;
        $this->docLabels      = $docLabels;

        if (false !== strpos($this->type, '[]')) {
            $this->isArray       = true;
            $this->type          = str_replace('[]', '', $this->type);
        }
    }

    public function getDocDesc(): string
    {
        return $this->docDesc;
    }

    public function getDocLabels(): array
    {
        return $this->docLabels;
    }

    public function getDocLabel(string $key): array
    {
        return $this->docLabels[$key] ?? [];
    }

    public function getValue()
    {
        return $this->value;
    }
}
