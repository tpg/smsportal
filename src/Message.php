<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use TPG\SMSPortal\Contracts\Data;

class Message implements Data
{
    public function __construct(
        protected string $destination,
        protected string $content,
        protected ?LandingPage $landingPage = null,
        protected ?string $customerId = null
    )
    {
    }

    public function withCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function withLandingPage(LandingPage $landingPage): self
    {
        $this->landingPage = $landingPage;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'destination' => $this->destination,
            'content' => $this->content,
            'customerId' => $this->customerId,
            'landingPageVariables' => $this->landingPage->toArray(),
        ];
    }
}
