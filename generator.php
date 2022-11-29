<?php

use Rice\Basic\Support\Generate\Documentation\HeadCommonGenerator;
use Rice\Basic\Support\Generate\Tokenizer\Tokens;

require_once './vendor/autoload.php';

(new HeadCommonGenerator())->apply(Tokens::fromCode(file_get_contents(__DIR__ . '/tests/Support/Annotation/Cat.php')));

