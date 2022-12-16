<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Tests\Support\Annotation\Cat;
use Rice\Basic\Support\FileNamespace;

class FileNamespaceTest extends TestCase
{
    public function testGetNamespaces(): void
    {
        FileNamespace::getInstance()->matchNamespace(Cat::class, __DIR__ . DIRECTORY_SEPARATOR . 'Annotation' . DIRECTORY_SEPARATOR . 'Cat.php');
        $uses = FileNamespace::getInstance()->getUses();

        $this->assertArrayHasKey(Cat::class, $uses);
    }
}
