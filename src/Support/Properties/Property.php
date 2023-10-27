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
     * 注释描述.
     */
    public string $docDesc;

    /**
     * 注释@相关值
     */
    public array $docLabels = [];

    /**
     * 类名称.
     */
    public string $className;

    /**
     * 类命名空间.
     */
    public string $classNamespace;

    /**
     * 该属性若是对象的话，必然会存在一个命名空间
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

    public function __construct(
        ?string $type,
        $className,
        $classNamespace,
        $name,
        $value,
        $docComment)
    {
        $this->type           = $type;
        $this->className      = $className;
        $this->classNamespace = $classNamespace;
        $this->name           = $name;
        $this->value          = $value;
        $this->docComment     = $docComment;

        $this->matchLabels();
        $this->docDesc = $this->parseDocDesc();

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

    public function parseDocDesc(): string
    {
        $lines   = explode(PHP_EOL, $this->docComment);
        $descArr = [];
        foreach ($lines as $line) {
            $newLine = trim(ltrim(trim($line), '/*'), ' ');
            $docLine = $this->parseDocLine($newLine);
            if ($docLine) {
                $descArr[] = $docLine;
            }
        }

        if (empty($descArr)) {
            return '';
        }

        return count($descArr) > 1 ? implode(PHP_EOL, $descArr) : $descArr[0];
    }

    private function parseDocLine(string $line): string
    {
        if (empty($line)) {
            return '';
        }

        if (0 === strpos($line, '@')) {
            return '';
        }

        return $line;
    }

    public function getDocDesc(): string
    {
        return $this->docDesc;
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
