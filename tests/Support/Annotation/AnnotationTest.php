<?php

namespace Tests\Support\Annotation;

use ReflectionException;
use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Support\Annotation\Annotation;

class AnnotationTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testAnnotation(): void
    {
        $annotation = new Annotation();

        $this->assertIsArray($annotation->execute(Cat::class)->getUses());
    }

    /**
     * @throws ReflectionException
     */
    public function testProperty(): void
    {
        $annotation = new Annotation();

        $property = $annotation->execute(Cat::class)->getProperty();
        $this->assertArrayHasKey(Cat::class, $property);
    }
}
