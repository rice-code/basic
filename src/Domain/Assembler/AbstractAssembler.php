<?php

namespace Rice\Basic\Domain\Assembler;

use Rice\Basic\Domain\Assembler\Traits\AssemblerTrait;
use Rice\Basic\Domain\Assembler\Interfaces\BaseAssembler;
use Rice\Basic\Domain\Assembler\Interfaces\DtoAssemblerInterface;
use Rice\Basic\Domain\Assembler\Interfaces\EntityAssemblerInterface;
use Rice\Basic\Domain\Assembler\Interfaces\ArrayConverterInterface;

abstract class AbstractAssembler implements BaseAssembler, DtoAssemblerInterface, EntityAssemblerInterface, ArrayConverterInterface
{
    use AssemblerTrait;

    abstract public function getDTOClass(): string;
    abstract public function getEntityClass(): string;
    abstract public function toDTO(object $entity): object;
    abstract public function toEntity(object $dto): object;
}