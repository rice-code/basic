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
     * 属性的注释.
     */
    public string $docComment;

    /**
     * 注释@相关值
     */
    public array $docLabels = [];

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

    public function __construct(?string $type, $name, $value, $docComment)
    {
        $this->type          = $type;
        $this->name          = $name;
        $this->value         = $value;
        $this->docComment    = $docComment;

        $this->matchLabels();

        if (!is_null($type)) {
            $this->stronglyTyped = true;
        }

        if (!$this->stronglyTyped && isset($this->docLabels['var'])) {
            $this->type = $this->docLabels['var'][0];
        }

        if (false !== strpos($this->type, '[]')) {
            $this->isArray       = true;
            $this->type          = str_replace('[]', '', $this->type);
        }
    }

    protected function matchLabels(): void
    {
        preg_match_all(self::LABEL_PATTERN, $this->docComment, $matches);
        $cnt = count($matches[0]);
        for ($i = 0; $i < $cnt; ++$i) {
            $this->docLabels[$matches[1][$i]][] = $matches[2][$i];
        }
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
