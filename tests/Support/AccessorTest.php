<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Exception\BaseException;
use Rice\Basic\Exception\SupportException;
use Tests\Support\Entity\Cat;
use Tests\Support\Entity\GetterCat;
use Tests\Support\Entity\SetterCat;

class AccessorTest extends TestCase
{
    /**
     * @throws SupportException
     * @throws ReflectionException
     */
    public function testAccessor(): void
    {
        $cat = new Cat(['eyes' => 'blue']);

        $this->assertEquals('blue', $cat->getEyes());

        $setterCat = new SetterCat(['eyes' => 'blue']);

        $setterCat->setEyes('red');
        try {
            $setterCat->getEyes();
        } catch (\Exception $e) {
            $this->assertEquals(BaseEnum::METHOD_NOT_DEFINE, $e->getMessage());
        }

        $getterCat = new GetterCat(['eyes' => 'blue']);
        $this->assertEquals('blue', $getterCat->getEyes());
        try {
            $getterCat->setEyes('red');
        } catch (\Exception $e) {
            $this->assertEquals(BaseEnum::METHOD_NOT_DEFINE, $e->getMessage());
        }

    }
}