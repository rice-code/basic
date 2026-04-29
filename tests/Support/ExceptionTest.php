<?php

namespace Tests\Support;

use Rice\Basic\Support\Lang;
use PHPUnit\Framework\TestCase;
use Rice\Basic\Infrastructure\Enum\SupportEnum;
use Rice\Basic\Infrastructure\Enum\HttpStatusCodeEnum;
use Rice\Basic\Infrastructure\Enum\InvalidRequestEnum;
use Rice\Basic\Infrastructure\Exception\InvalidRequestException;
use Rice\Basic\Infrastructure\Exception\InternalServerErrorException;

class ExceptionTest extends TestCase
{
    public function testI18n(): void
    {
        try {
            Lang::getInstance()->setLocale('en');

            throw new InternalServerErrorException(SupportEnum::METHOD_NOT_DEFINE);
        } catch (InternalServerErrorException $e) {
            $this->assertEquals('method not define', $e->getMessage());
        }

        try {
            Lang::getInstance()->setLocale('en');

            throw new InvalidRequestException(InvalidRequestEnum::DEFAULT);
        } catch (InvalidRequestException $e) {
            $this->assertEquals('Business Error', $e->getMessage());
        }

        try {
            Lang::getInstance()->setLocale('en');

            InvalidRequestException::default();
        } catch (InvalidRequestException $e) {
            $this->assertEquals(HttpStatusCodeEnum::INVALID_REQUEST, $e::httpStatusCode());
            $this->assertEquals('Business Error', $e->getMessage());
        }
    }
}
