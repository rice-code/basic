<?php


namespace Tests\Support\Annotation;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Annotation\Annotation;

class AnnotationTest extends TestCase
{
    public function testAnnotation(): void
    {
        $annotation = new Annotation();

        $this->assertIsArray($annotation->execute(Cat::class)->getNamespaceList());
    }

    public function testProperty(): void
    {
        $annotation = new Annotation();

        var_dump($annotation->execute(Cat::class)->getProperty());
    }
}