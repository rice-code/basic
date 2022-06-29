<?php


namespace Rice\Basic\Support\Traits;


use Rice\Basic\Exception\TypeException;
use Rice\Basic\Lang;

trait AutoFillTrait
{
    public function __construct($params)
    {
        if (!empty($params)) {
            $this->handle($params);
        }
    }

    protected function handle($params)
    {
        if (!is_object($params) || !is_array($params)) {
            new TypeException(Lang::getInstance()->setKey());
        }


        $this->beforeFillHook();


        $this->fill();


        $this->afterFillHook();


    }


    public function fill()
    {
        $vars = $this->getPublicVars();
    }


    public function beforeFillHook() {}


    public function afterFillHook() {}


    public function getPublicVars (): array
    {
        $me = new class {
            public function getPublicVars($object): array
            {
                return get_object_vars($object);
            }
        };
        return $me->getPublicVars($this);
    }
}