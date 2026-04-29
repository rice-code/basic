<?php

namespace Rice\Basic\Domain\Assembler\Interfaces;

interface DtoAssemblerInterface
{
    public function toDTO(object $entity): object;
    public function getDTOClass(): string;
}