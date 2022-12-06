<?php

namespace Tests\Support\Annotation;

use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Generate\Annotation\Annotation;

class AnnotationTest extends TestCase
{
    public function testAnnotation(): void
    {
        $annotation = new Annotation();

        $this->assertIsArray($annotation->execute(Cat::class)->getUses());
    }

    public function testProperty(): void
    {
        $annotation = new Annotation();

        $property = $annotation->execute(Cat::class)->getProperty();
        $this->assertArrayHasKey(Cat::class, $property);
    }
}
