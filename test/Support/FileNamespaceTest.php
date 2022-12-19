<?php

namespace Test\Support;

use Test\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileNamespace;

class FileNamespaceTest extends TestCase
{
    public function testGetNamespaces(): void
    {
        FileNamespace::getInstance()->matchNamespace(Cat::class, __DIR__ . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . 'Cat.php');
        $uses = FileNamespace::getInstance()->getUses();

        $this->assertArrayHasKey(Cat::class, $uses);
    }
}
