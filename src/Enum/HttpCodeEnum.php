<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Enum;


class HttpCodeEnum
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_EXPECTATION_FAILED = 417;
    public const HTTP_UNPROCESSABLE_ENTITY = 426;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
}