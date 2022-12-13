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
        $this->test      = $this->project . 'tests' . DIRECTORY_SEPARATOR;
        $this->assembler = $this->src . 'Assembler' . DIRECTORY_SEPARATOR;
        $this->console   = $this->src . 'Console' . DIRECTORY_SEPARATOR;
        $this->dto       = $this->src . 'DTO' . DIRECTORY_SEPARATOR;
        $this->entity    = $this->src . 'Entity' . DIRECTORY_SEPARATOR;
        $this->enum      = $this->src . 'Enum' . DIRECTORY_SEPARATOR;
        $this->exception = $this->src . 'Exception' . DIRECTORY_SEPARATOR;
        $this->lang      = $this->src . 'Lang' . DIRECTORY_SEPARATOR;
        $this->support   = $this->src . 'Support' . DIRECTORY_SEPARATOR;
    }
}