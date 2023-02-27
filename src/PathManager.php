<?php

namespace Rice\Basic;

use Rice\Basic\Support\Traits\Singleton;

/**
 * Class PathManager.
 *
 * @internal
 */
class PathManager
{
    use Singleton;

    public string $project;
    public string $cache;
    public string $src;
    public string $test;
    public string $components;
    public string $assembler;
    public string $console;
    public string $dto;
    public string $entity;
    public string $enum;
    public string $exception;
    public string $lang;
    public string $support;
    public string $template;

    public function __construct()
    {
        $this->project    = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $this->cache      = $this->project . 'cache' . DIRECTORY_SEPARATOR;
        $this->src        = $this->project . 'src' . DIRECTORY_SEPARATOR;
        $this->test       = $this->project . 'tests' . DIRECTORY_SEPARATOR;
        $this->components = $this->src . 'components' . DIRECTORY_SEPARATOR;
        $this->assembler  = $this->components . 'Assembler' . DIRECTORY_SEPARATOR;
        $this->dto        = $this->components . 'DTO' . DIRECTORY_SEPARATOR;
        $this->entity     = $this->components . 'Entity' . DIRECTORY_SEPARATOR;
        $this->enum       = $this->components . 'Enum' . DIRECTORY_SEPARATOR;
        $this->exception  = $this->components . 'Exception' . DIRECTORY_SEPARATOR;
        $this->console    = $this->src . 'Console' . DIRECTORY_SEPARATOR;
        $this->lang       = $this->src . 'Lang' . DIRECTORY_SEPARATOR;
        $this->support    = $this->src . 'Support' . DIRECTORY_SEPARATOR;
        $this->template   = $this->src . 'Template' . DIRECTORY_SEPARATOR;
    }
}
