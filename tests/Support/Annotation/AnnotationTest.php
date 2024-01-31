<?php

namespace Tests\Support\Annotation;

use ReflectionException;
use Rice\Basic\Support\Lang;
use Rice\Basic\Support\Utils\FrameTypeUtil;
use Rice\Basic\Support\Utils\SplUtil;
use Rice\Basic\Support\Utils\VerifyUtil;
use Tests\Support\Entity\Cat;
use Tests\Support\Entity\Cat8;
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
        $annotation = new Annotation();

        $this->assertIsArray($annotation->execute(Cat::class)->getUses());
    }

    /**
     * @throws ReflectionException
     */
    public function testProperty(): void
    {
        $annotation = new Annotation();

        $properties = $annotation->execute(Cat::class)->getClassProperties();
        $this->assertArrayHasKey(Cat::class, $properties);
        /**
         * @var Property $eyes
         */
        $eyes = $properties[Cat::class]['eyes'];
        $this->assertEquals('$this', $eyes->getDocLabels()['return'][0]);
        $this->assertEquals('眼睛.', $eyes->getDocDesc());
    }

    /**
     * @throws ReflectionException
     */
    public function testProperty8(): void
    {
        // 只对 php8 进行测试
        if (FrameTypeUtil::isPHP(7)) {
            $this->assertTrue(true);
            return;
        }
        $annotation = new Annotation();

        $properties = $annotation->execute(Cat8::class)->getClassProperties();
        $this->assertArrayHasKey(Cat8::class, $properties);
        /**
         * @var Property $eyes
         */
        $eyes = $properties[Cat8::class]['eyes'];
        $this->assertEquals('眼睛', $eyes->getDocDesc());
    }

    public function testLang()
    {
        $annotation = new Annotation();
        $annotation->setFilter(\ReflectionProperty::IS_PUBLIC);
        $properties = $annotation->execute(SupportEnum::class)->getClassProperties();
        $locale     = Lang::getInstance()->setLocale('en')->getLocale();
        $this->assertEquals(
            'cannot divide by zero',
            $properties[SupportEnum::class]['CANNOT_DIVIDE_BY_ZERO']->getDocLabel($locale)[0]
        );
    }
}
