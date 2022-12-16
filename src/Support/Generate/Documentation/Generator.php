<?php

namespace Rice\Basic\Support\Generate\Documentation;

use Rice\Basic\Enum\BaseEnum;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Rice\Basic\Exception\SupportException;
use Symfony\Component\Filesystem\Filesystem;
use PhpCsFixer\Tokenizer\Analyzer\NamespacesAnalyzer;

abstract class Generator
{
    /**
     * 文件路径.
     *
     * @var string
     */
    public $filePath;

    /**
     * 文件token.
     *
     * @var Tokens
     */
    protected $tokens;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;

        if (!(new Filesystem())->exists($this->filePath)) {
            throw new SupportException(BaseEnum::FILE_NOT_EXISTS);
        }

        $content      = file_get_contents($this->filePath);
        $this->tokens = Tokens::fromCode($content);
    }

    protected function getCommentBlock($lines): string
    {
        $comment = '/**' . PHP_EOL;

        foreach ($lines as $line) {
            $comment .= rtrim(' * ' . $line) . PHP_EOL;
        }

        return $comment . ' */' . PHP_EOL;
    }

    protected function getNamespace()
    {
        return (new NamespacesAnalyzer())->getDeclarations($this->tokens);
    }

    public function getClassName()
    {
        $maxLen = count($this->tokens);
        foreach ($this->tokens as $idx => $token) {
            /**
             * @var Token $token
             */
            if (!$token->isGivenKind(T_CLASS)) {
                continue;
            }

            while ($idx < $maxLen) {
                if (T_WHITESPACE !== $this->tokens[++$idx]->getId()) {
                    return $this->tokens[$idx]->getContent();
                }
            }

            throw new \Exception('this file not class');
        }
    }

    abstract public function apply();
}
