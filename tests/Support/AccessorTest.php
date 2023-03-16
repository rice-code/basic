<?php

namespace Tests\Support;

use ReflectionException;
use Rice\Basic\Support\Lang;
use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Tests\Support\Entity\GetterCat;
use Tests\Support\Entity\SetterCat;
use Rice\Basic\components\Exception\SupportException;

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

        Lang::getInstance()->setLocale('en');
        $setterCat->setEyes('red');

        try {
            $setterCat->getEyes();
        } catch (\Exception $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }

        $getterCat = new GetterCat(['eyes' => 'blue']);
        $this->assertEquals('blue', $getterCat->getEyes());

        try {
            $getterCat->setEyes('red');
        } catch (\Exception $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }
    }
}
