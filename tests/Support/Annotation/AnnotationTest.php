<?php

namespace Tests\Support\Annotation;

use ReflectionException;
use Rice\Basic\Support\Lang;
use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Components\Enum\SupportEnum;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Annotation\Annotation;

class AnnotationTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testAnnotation(): void
    {
        $annotation = Annotation::getInstance();

        $this->assertIsArray($annotation->execute(Cat::class)->getUses());
    }

    /**
     * @throws ReflectionException
     */
    public function testProperty(): void
    {
        $annotation = Annotation::getInstance();

        $properties = $annotation->execute(Cat::class)->getClassProperties();
        $this->assertArrayHasKey(Cat::class, $properties);
        /**
         * @var Property $eyes
         */
        $eyes = $properties[Cat::class]['eyes'];
        $this->assertEquals('$this', $eyes->getDocLabels()['return'][0]);
        $this->assertEquals('眼睛.', $eyes->getDocDesc());
    }

    public function testLang()
    {
        $annotation = Annotation::getInstance();
        $annotation->setFilter(\ReflectionProperty::IS_PUBLIC);
        $properties = $annotation->execute(SupportEnum::class)->getClassProperties();
        $locale     = Lang::getInstance()->getLocale();
        $this->assertEquals(
            'cannot divide by zero',
            $properties[SupportEnum::class]['CANNOT_DIVIDE_BY_ZERO']->getDocLabel($locale)[0]
        );
    }
}
