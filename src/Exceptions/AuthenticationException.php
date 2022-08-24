<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Exceptions;

use Throwable;

class AuthenticationException extends \Exception
{
    public function __construct(int $responseCode, ?Throwable $previous = null)
    {
        parent::__construct('Unable to authenticate with SMSPortal', $responseCode, $previous);
    }
}
