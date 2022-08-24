<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use Carbon\CarbonInterface;
use TPG\SMSPortal\Contracts\Data;

class SendOptions implements Data
{
    public function __construct(
        protected readonly ?bool $allowContentTrimming = null,
        protected readonly ?string $senderId = null,
        protected readonly ?bool $duplicateCheck = null,
        protected readonly ?CarbonInterface $startDeliveryUtc = null,
        protected readonly ?CarbonInterface $endDeliveryUtc = null,
        protected readonly ?string $replyRuleSetName = null,
        protected readonly ?string $campaignName = null,
        protected readonly ?string $costCentre = null,
        protected readonly ?bool $checkOptOuts = null,
        protected readonly ?bool $shortenUrls = null,
        protected readonly ?int $validityPeriod = null,
        protected readonly ?bool $testMode = null,
        protected readonly ?string $ruleName = null,
        protected readonly ?int $replyRuleVersion = null,
        protected readonly ?string $extraForwardEmails = null,
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'allowContentTrimming' => $this->allowContentTrimming,
            'senderId' => $this->senderId,
            'duplicateCheck' => $this->duplicateCheck,
            'startDeliveryUtc' => $this->startDeliveryUtc?->toIso8601String(),
            'endDeliveryUtc' => $this->endDeliveryUtc?->toIso8601String(),
            'replyRuleSetName' => $this->replyRuleSetName,
            'campaignName' => $this->campaignName,
            'costCentre' => $this->costCentre,
            'checkOptOuts' => $this->checkOptOuts,
            'shortenUrls' => $this->shortenUrls,
            'validityPeriod' => $this->validityPeriod,
            'testMode' => $this->testMode,
            'rulename' => $this->ruleName,
            'replyRuleVersion' => $this->replyRuleVersion,
            'extraForwardEmails' => $this->extraForwardEmails,
        ], fn ($value) => $value !== null);
    }
}
