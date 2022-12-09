<?php


namespace Rice\Basic\Exception;


class AssemblerException extends BaseException
{
    public static function getLangName(): string
    {
        return 'assembler';
    }
}