<?php

namespace Rice\Basic\Domain\Assembler\Interfaces;

interface EntityAssemblerInterface
{
    public function toEntity(object $dto): object;
    public function getEntityClass(): string;
}