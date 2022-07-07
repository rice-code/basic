<?php


namespace Tests\Support\Annotation;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Annotation\Annotation;

class AnnotationTest extends TestCase
{
    public function testAnnotation()
    {
        $annotation = new Annotation();

        var_dump($annotation->execute(Cat::class)->getNamespaceList());
    }
}