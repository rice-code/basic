<?php

namespace Rice\Basic\Components\Enum;

class HttpStatusCodeEnum
{
    /**
     * Bad Request
     */
    public const INVALID_REQUEST = 400;
    /**
     * Not Found
     */
    public const RESOURCE_NOT_FOUND = 404;
    /**
     * Method Not Allowed
     */
    public const METHOD_NOT_SUPPORTED = 405;
    /**
     * Not Acceptable
     */
    public const MEDIA_TYPE_NOT_ACCEPTABLE = 406;
    /**
     * Conflict
     */
    public const RESOURCE_CONFLICT = 409;
    /**
     * Unsupported Media Type
     */
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * Unprocessable Entity
     */
    public const UNPROCESSABLE_ENTITY = 422;
    /**
     * Too Many Requests
     */
    public const RATE_LIMIT_REACHED = 429;

    /**
     * Internal Server Error
     */
    public const INTERNAL_SERVER_ERROR = 500;
    /**
     * Service Unavailable
     */
    public const SERVICE_UNAVAILABLE = 503;
}