<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileNamespace;
use Tests\Support\Annotation\Cat;

class FileNamespaceTest extends TestCase {
    public function testGetNamespaces(): void {
        FileNamespace::getInstance()->matchNamespace(Cat::class, __DIR__ . DIRECTORY_SEPARATOR . 'Annotation' . DIRECTORY_SEPARATOR . 'Cat.php');
        $namespaces = FileNamespace::getInstance()->getNamespaces();
        $this->assertArrayHasKey(Cat::class, $namespaces);
    }
}
