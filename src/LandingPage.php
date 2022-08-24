<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use TPG\SMSPortal\Contracts\Data;

class LandingPage implements Data
{
    public function __construct(
        protected ?string $landingPageId = null,
        protected ?int $version = null,
        protected ?string $password = null,
        protected ?array $variables = [],
    )
    {
    }

    public function toArray(): array
    {
        return [
            'landingPageId' => $this->landingPageId,
            'version' => $this->version,
            'password' => $this->password,
            'variables' => $this->variables,
        ];
    }
}
