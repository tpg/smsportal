<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Responses;

use Illuminate\Support\Collection;
use TPG\SMSPortal\Responses\Faults;

class ErrorReport
{
    public function __construct(
        public readonly int $noNetwork,
        public readonly int $noContents,
        public readonly int $contentToLong,
        public readonly int $duplicates,
        public readonly int $optedOuts,
        public readonly array $faults,
    )
    {
    }

    public function faults(): Collection
    {
        return collect($this->faults)->map(fn (array $faults) => new Faults(...$faults));
    }
}
