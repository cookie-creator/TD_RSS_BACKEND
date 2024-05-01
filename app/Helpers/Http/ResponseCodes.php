<?php

namespace App\Helpers\Http;

interface ResponseCodes
{
    public const HTTP_SUCCESS = 200;
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_CONFLICT = 409;
    public const HTTP_UNPROCESSABLE = 422;
}
