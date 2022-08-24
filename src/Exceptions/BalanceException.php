<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Exceptions;

use Throwable;

class BalanceException extends \Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Unable to retrieve balance from SMSPortal', $previous?->getCode() ?? 0, $previous);
    }
}
