<?php

namespace Rice\Basic\Infrastructure\Enum;

class HttpStatusCodeEnum
{
    public const INVALID_REQUEST = 400;
    public const RESOURCE_NOT_FOUND = 404;
    public const METHOD_NOT_SUPPORTED = 405;
    public const MEDIA_TYPE_NOT_ACCEPTABLE = 406;
    public const RESOURCE_CONFLICT = 409;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const UNPROCESSABLE_ENTITY = 422;
    public const RATE_LIMIT_REACHED = 429;
    public const INTERNAL_SERVER_ERROR = 500;
    public const SERVICE_UNAVAILABLE = 503;
}