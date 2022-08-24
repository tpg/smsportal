<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Responses;

class Faults
{
    public function __construct(
        public readonly string $rawDestination,
        public readonly string $scrubbedDestination,
        public readonly string $customerId,
        public readonly string $errorMessage,
        public readonly string $status,
    )
    {
    }
}
