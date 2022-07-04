<?php


namespace Tests\Support;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileNamespace;
use Tests\Support\Annotation\Cat;

class FileNamespaceTest extends TestCase
{
    public function testGetNamespaces()
    {
        FileNamespace::getInstance()->matchNamespace(Cat::class, __DIR__ . DIRECTORY_SEPARATOR . 'Annotation' . DIRECTORY_SEPARATOR . 'Cat.php');
        var_dump(FileNamespace::getInstance()->getNamespaces());

    }
}