<?php

namespace Tests\Support\Annotation;

use ReflectionException;
use Rice\Basic\Support\Lang;
use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\SupportEnum;
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

        $property = $annotation->execute(Cat::class)->getProperties();
        $this->assertArrayHasKey(Cat::class, $property);
        $this->assertEquals('$this', $property[Cat::class]['eyes']->getDocLabels()['return'][0]);
    }

    public function testLang()
    {
        $annotation = new Annotation();
        $annotation->setFilter(\ReflectionProperty::IS_PUBLIC);
        $properties = $annotation->execute(SupportEnum::class)->getProperties();
        $locale     = Lang::getInstance()->getLocale();
        $this->assertEquals(
            'cannot divide by zero',
            $properties[SupportEnum::class]['CANNOT_DIVIDE_BY_ZERO']->getDocLabel($locale)[0]
        );
    }
}
