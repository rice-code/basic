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

        // 识别各种形式的数组类型声明
        if (!is_null($this->type)) {
            // 处理 [] 后缀格式（如 Eye[]）
            if (false !== strpos($this->type, '[]')) {
                $this->isArray = true;
                $this->type    = str_replace('[]', '', $this->type);
            }
            // 处理 array 关键词（如 array, array<string>, array<int, string>）
            elseif (0 === strpos(strtolower($this->type), 'array')) {
                $this->isArray = true;
                // 提取数组中的类型（如果有）
                preg_match('/array<([^,>]+)/i', $this->type, $matches);
                if (!empty($matches[1])) {
                    $this->type = $matches[1];
                } else {
                    $this->type = null; // 未知数组元素类型
                }
            }
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
