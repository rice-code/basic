<?php

namespace Rice\Basic\Support\Properties;

use Rice\Basic\Support\Annotation\tags\Doc;
use Rice\Basic\Support\Utils\FrameTypeUtil;

class DocComment
{
    public const LABEL_PATTERN = '/.*@(\S+)[ ]+([^\t\n\r]+)/';

    public static function getConstantInfo(\ReflectionClassConstant $constant): array
    {
        $name       = $constant->getName();
        $value      = $constant->getValue();
        $docComment = $constant->getDocComment();

        $labels  = self::matchLabels($docComment);
        $comment = self::parseDocDesc($docComment);

        return [$name, $value, $comment, $labels];
    }

    public static function getPropertyInfo(\ReflectionProperty $property): array
    {
        $type = $property->getType();
        $name = $property->getName();

        $type  = $type instanceof \ReflectionType ? $type->getName() : null;

        $stronglyTyped = !is_null($type);

        $comment = '';

        // 若是 php8 版本，默认查询是否定义 Doc 注解标签
        if (is_null($type) && FrameTypeUtil::isPHP(8) && $docs = $property->getAttributes(Doc::class)) {
            $type    = $docs[0]->getArguments()['var'];
            $comment = $docs[0]->getArguments()['text'];
        }

        $docComment = $property->getDocComment() ?: '';
        $labels     = self::matchLabels($docComment);

        // 注释兜底
        if (is_null($type) && isset($labels['var'])) {
            $type = $labels['var'][0];
        }
        // php8 版本注解未找到属性描述，尝试注释中找
        if (!$comment) {
            $comment = self::parseDocDesc($docComment);
        }

        return [$type, $name, $comment, $stronglyTyped, $labels];
    }

    protected static function matchLabels(string $docComment): array
    {
        $docLabels = [];
        preg_match_all(self::LABEL_PATTERN, $docComment, $matches);
        $cnt = count($matches[0]);
        for ($i = 0; $i < $cnt; ++$i) {
            $docLabels[$matches[1][$i]][] = $matches[2][$i];
        }

        return $docLabels;
    }

    protected static function parseDocDesc($docComment): string
    {
        $lines   = explode(PHP_EOL, $docComment);

        if (1 === count($lines)) {
            // windows下兼容\n换行
            $lines = explode("\n", $docComment);
        }

        $descArr = [];

        foreach ($lines as $line) {
            $newLine = trim(ltrim(trim($line), '/*'), ' ');
            $docLine = self::parseDocLine($newLine);
            if ($docLine) {
                $descArr[] = $docLine;
            }
        }

        if (empty($descArr)) {
            return '';
        }

        return count($descArr) > 1 ? implode(PHP_EOL, $descArr) : $descArr[0];
    }

    protected static function parseDocLine(string $line): string
    {
        if (empty($line)) {
            return '';
        }

        if (str_contains($line, '@')) {
            return '';
        }

        return $line;
    }
}
