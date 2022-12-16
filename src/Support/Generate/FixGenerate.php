<?php

namespace Rice\Basic\Support\Generate;

use PhpCsFixer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class FixGenerate
{
    public static function handle($dirPath)
    {
        (new Application())->run(new ArrayInput([
            'fix',
            $dirPath,
        ]));
    }
}
