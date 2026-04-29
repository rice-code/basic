<?php

namespace Rice\Basic\Domain\Assembler\Interfaces;

interface ArrayConverterInterface
{
    public function toArray(object $object);
    public function fromArrayToDTO(array $data);
    public function fromArrayToEntity(array $data);
}