<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Enums;

enum ResponseStatus: int
{
    case Ok = 200;
    case BadRequest = 400;
    case Unauthorized = 401;
    case InternalServerError = 500;
    case ServiceUnavailable = 503;

    public function presentable(): string
    {
        return match($this) {
            self::Ok => 'All good',
            self::BadRequest => 'The request was invalid',
            self::Unauthorized => 'Invalid or missing auth token',
            self::InternalServerError => 'A server error occurred',
            self::ServiceUnavailable => 'SMSPortal is currently unavailable',
        };
    }
}
