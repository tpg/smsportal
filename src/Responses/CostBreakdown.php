<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Responses;

class CostBreakdown
{
    public function __construct(
        public readonly int $quantity,
        public readonly float $cost,
        public readonly string $network,
    )
    {
    }
}
