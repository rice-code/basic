<?php

namespace Rice\Basic\Support\Generate;

use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\Filesystem\Filesystem;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use Symfony\Component\Filesystem\Exception\IOException;

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
     * @var Tokens
     */
    protected $tokens;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;

        if (!(new Filesystem())->exists($this->filePath)) {
            throw new IOException('not exists file');
        }

        $content      = file_get_contents($this->filePath);
        $this->tokens = Tokens::fromCode($content);
    }

    /**
     * 元素对齐.
     * @return $this
     */
    protected function alignCommentBlock(): self
    {
        $tags = [
            'param',
            'property',
            'property-read',
            'property-write',
            'return',
            'throws',
            'type',
            'var',
            'method',
        ];
        $fixer = (new PhpdocAlignFixer());
        $fixer->configure(['align' => 'vertical', 'tags' => $tags]);
        $fixer->fix(new \SplFileInfo($this->filePath), $this->tokens);

        return $this;
    }

    protected function getCommentBlock($lines): string
    {
        $comment = '/**' . PHP_EOL;

        foreach ($lines as $line) {
            $comment .= rtrim(' * ' . $line) . PHP_EOL;
        }

        return $comment . ' */' . PHP_EOL;
    }

    abstract public function apply();
}
