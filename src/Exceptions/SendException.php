<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Exceptions;

use Throwable;

class SendException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
