<?php


namespace Tests\Support;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileNamespace;

class FileNamespaceTest extends TestCase
{
    public function testGetNamespaces()
    {
        $fileNamespace = new FileNamespace();
        $fileNamespace->matchNamespace(__DIR__ . DIRECTORY_SEPARATOR . 'Annotation' . DIRECTORY_SEPARATOR . 'Cat.php');
        var_dump($fileNamespace->getNamespaces());

    }
}