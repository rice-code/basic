<?php


namespace Rice\Basic;


use Rice\Basic\Support\Traits\Singleton;

/**
 *
 * Class PathManager
 *
 * @internal
 * @package Rice\Basic
 */
class PathManager
{
    use Singleton;

    public string $project;
    public string $src;
    public string $test;
    public string $assembler;
    public string $console;
    public string $dto;
    public string $entity;
    public string $enum;
    public string $exception;
    public string $lang;
    public string $support;

    public function __construct()
    {
        $this->project   = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $this->src       = $this->project . 'src' . DIRECTORY_SEPARATOR;
        $this->test      = $this->project . 'test' . DIRECTORY_SEPARATOR;
        $this->assembler = $this->src . 'assembler' . DIRECTORY_SEPARATOR;
        $this->console   = $this->src . 'console' . DIRECTORY_SEPARATOR;
        $this->dto       = $this->src . 'dto' . DIRECTORY_SEPARATOR;
        $this->entity    = $this->src . 'entity' . DIRECTORY_SEPARATOR;
        $this->enum      = $this->src . 'enum' . DIRECTORY_SEPARATOR;
        $this->exception = $this->src . 'exception' . DIRECTORY_SEPARATOR;
        $this->lang      = $this->src . 'lang' . DIRECTORY_SEPARATOR;
        $this->support   = $this->src . 'support' . DIRECTORY_SEPARATOR;
    }
}