<?php

namespace Tests\Support;

use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\FileParser;

class FileNamespaceTest extends TestCase
{
    public function testGetNamespaces(): void
    {
        FileParser::getInstance()->execute(Cat::class, __DIR__ . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . 'Cat.php');
        $uses = FileParser::getInstance()->getUses();

        $this->assertArrayHasKey(Cat::class, $uses);
    }
}
