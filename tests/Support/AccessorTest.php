<?php

namespace Tests\Support;

use ReflectionException;
use Rice\Basic\Support\Lang;
use Tests\Support\Entity\Cat;
use PHPUnit\Framework\TestCase;
use Tests\Support\Entity\GetterCat;
use Tests\Support\Entity\SetterCat;
use Rice\Basic\Components\Exception\SupportException;

class AccessorTest extends TestCase
{
    /**
     * @throws SupportException
     * @throws ReflectionException
     */
    public function testAccessor(): void
    {
        $cat = new Cat(['eyes' => [['size' => 'big'], ['size' => 'small']]]);

        $this->assertEquals('big', $cat->getEyes()[0]->getSize());

        $setterCat = new SetterCat(['eyes' => [['size' => 'big'], ['size' => 'small']]]);

        Lang::getInstance()->setLocale('en');
        $setterCat->setEyes('red');

        try {
            $setterCat->getEyes();
        } catch (\Exception $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }

        $getterCat = new GetterCat(['eyes' => [['size' => 'big'], ['size' => 'small']]]);
        $this->assertEquals('small', $getterCat->getEyes()[1]->getSize());

        try {
            $getterCat->setEyes([['size' => 'big'], ['size' => 'small']]);
        } catch (\Exception $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }
    }
}
