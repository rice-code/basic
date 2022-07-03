<?php


namespace Tests\Support\Annotation;


use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Annotation;

class AnnotationTest extends TestCase
{
    public function testAnnotation()
    {
        $annotation = new Annotation();

        $annotation->buildClass(Cat::class)->analysisNamespaces()->analysisAttr();
    }
}