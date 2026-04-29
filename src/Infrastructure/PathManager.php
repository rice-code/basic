<?php

namespace Rice\Basic\Infrastructure;

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
    public string $domain;
    public string $infrastructure;
    public string $support;
    public string $contracts;
    public string $lang;
    public string $console;
    public string $template;

    public function __construct()
    {
        $this->project        = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $this->cache          = $this->project . 'cache' . DIRECTORY_SEPARATOR;
        $this->src            = $this->project . 'src' . DIRECTORY_SEPARATOR;
        $this->test           = $this->project . 'tests' . DIRECTORY_SEPARATOR;
        $this->domain         = $this->src . 'Domain' . DIRECTORY_SEPARATOR;
        $this->infrastructure = $this->src . 'Infrastructure' . DIRECTORY_SEPARATOR;
        $this->support        = $this->src . 'Support' . DIRECTORY_SEPARATOR;
        $this->contracts      = $this->src . 'Contracts' . DIRECTORY_SEPARATOR;
        $this->lang           = $this->project . 'lang' . DIRECTORY_SEPARATOR;
        $this->console        = $this->src . 'Console' . DIRECTORY_SEPARATOR;
        $this->template       = $this->src . 'Template' . DIRECTORY_SEPARATOR;
    }
}
