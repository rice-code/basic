<?php

namespace Rice\Basic\Support\Generate\Documentation;

use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\DocBlock\DocBlock;
use Rice\Basic\Support\Generate\Generator;
use Rice\Basic\Support\Generate\Properties\Property;
use Rice\Basic\Support\generate\Properties\Properties;

class AccessorGenerator extends Generator
{
    protected const CLASS_TOKENS = [T_CLASS, T_TRAIT, T_INTERFACE, T_ABSTRACT];

    protected $lines;

    protected $docMap;

    public function apply()
    {
        [$this->lines, $this->docMap] = $this->generateLines();

        for ($index = 0, $limit = \count($this->tokens); $index < $limit; ++$index) {
            /**
             * @var Token $token
             */
            $token = $this->tokens[$index];

            if (!$token->isGivenKind(self::CLASS_TOKENS)) {
                continue;
            }

            $idx = $this->tokens->getPrevTokenOfKind($index, [[T_DOC_COMMENT]]);

            if (null !== $idx) {
                $this->tokens[$idx] = new Token([T_DOC_COMMENT, $this->updateDoc($this->tokens[$idx])]);

                continue;
            }
            $this->tokens->insertAt($index, [new Token([T_DOC_COMMENT, $this->getCommentBlock($this->lines)])]);
        }

        $this->alignCommentBlock();
        file_put_contents($this->filePath, $this->tokens->generateCode());
    }

    public function generateLines()
    {
        $namespace  = $this->getNamespace()[0]->getFullName() . DIRECTORY_SEPARATOR . $this->getClassName();
        $properties = new Properties($namespace);
        $lines      = [];
        $docMap     = [];
        foreach ($properties->getProperties() as $property) {
            /**
             * @var Property $property
             */
            $propertyDocType = $this->getDocPropertyType($property->docComment);

            $name = ucfirst($property->name);
            if ('' !== $propertyDocType || null !== $property->getType()) {
                $typeName        = $property->type ? $property->name : $propertyDocType;
                $lines[]         = sprintf(
                    '@method self set%s(%s $value)',
                    $name,
                    $typeName
                );
                $docMap[$name][] = sprintf(
                    '@method self set%s(%s $value)',
                    $name,
                    $typeName
                );
                $lines[]         = sprintf(
                    '@method %s get%s()',
                    $typeName,
                    $name
                );
                $docMap[$name][] = sprintf(
                    '@method %s get%s()',
                    $typeName,
                    $name
                );

                continue;
            }
            $lines[]         = sprintf('@method self set%s($value)', $name);
            $docMap[$name][] = sprintf('set%s($value)', $name);
            $lines[]         = sprintf('@method get%s()', $name);
            $docMap[$name][] = sprintf('get%s()', $name);
        }

        return [$lines, $docMap];
    }

    public function updateDoc(Token $token): string
    {
        $doc   = new DocBlock($token->getContent());
        $lines = $doc->getLines();
        $len   = count($lines) - 1;
        foreach ($lines as $idx => $line) {

            if (!$line->containsUsefulContent()) {
                continue;
            }

            $len = $idx + 1;
            Preg::match('/@method.*set(\S+)\(/ux', $line->getContent(), $matchs);
            if (!empty($matchs)) {
                $content                     = Preg::replace('/@method\s*(.*\))/', $this->docMap[$matchs[1]][0], $line->getContent());
                $this->docMap[$matchs[1]][0] = trim($content, " \t\n\r\0\x0B*");
                $lines[$idx]->setContent('');
            }

            Preg::match('/@method.*get(\S+)\(/ux', $line->getContent(), $matchs);
            if (!empty($matchs)) {
                $content                     = Preg::replace('/@method\s*(.*\))/', $this->docMap[$matchs[1]][1], $line->getContent());
                $this->docMap[$matchs[1]][1] = trim($content, " \t\n\r\0\x0B*");
                $lines[$idx]->setContent('');
            }
        }

        [$firstArr, $secondArr] = array_chunk($lines, $len);

        foreach ($this->docMap as $item) {
            $firstArr[$len++] = ' * ' . $item[0] . PHP_EOL;
            $firstArr[$len++] = ' * ' . $item[1] . PHP_EOL;
        }

        return implode('', array_merge($firstArr, $secondArr));
    }

    public function getDocPropertyType($doc): string
    {
        if (false === $doc) {
            return '';
        }

        $docBlock = new DocBlock($doc);

        $types = '';

        foreach ($docBlock->getAnnotationsOfType('var') as $annotation) {
            $types = implode('|', $annotation->getTypes());
        }

        return $types;
    }
}
