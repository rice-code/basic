<?php

namespace Rice\Basic\Domain\Assembler\Traits;

use Rice\Basic\Domain\Mapper\ObjectMapper;
use Rice\Basic\Contracts\CacheContract;

trait AssemblerTrait
{
    protected static ?ObjectMapper $mapper = null;

    protected function getMapper(): ObjectMapper
    {
        if (is_null(self::$mapper)) {
            self::$mapper = new ObjectMapper();
        }

        return self::$mapper;
    }

    public function toArray(object $object, ?CacheContract $cache = null): array
    {
        return $this->getMapper()->toArray($object, $cache, true);
    }

    public function fromArrayToDTO(array $data, ?CacheContract $cache = null): object
    {
        return $this->getMapper()->fromArrayToObject($this->getDTOClass(), $data, $cache);
    }

    public function fromArrayToEntity(array $data, ?CacheContract $cache = null): object
    {
        return $this->getMapper()->fromArrayToObject($this->getEntityClass(), $data, $cache);
    }
}