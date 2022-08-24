<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Responses;

use TPG\SMSPortal\Responses\CostBreakdown;
use TPG\SMSPortal\Responses\ErrorReport;

class BulkMessageResponse
{
    public function __construct(
        public readonly float $cost,
        public readonly float $remainingBalance,
        public readonly int $eventId,
        public readonly string $sample,
        public readonly array $costBreakdown,
        public readonly int $messages,
        public readonly int $parts,
        public readonly array $errorReport,
    )
    {
    }

    public function costBreakdown(): CostBreakdown
    {
        return new CostBreakdown(...$this->costBreakdown);
    }

    public function error(): ErrorReport
    {
        return new ErrorReport(...$this->errorReport);
    }
}
