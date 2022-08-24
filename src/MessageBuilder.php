<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use ArrayAccess;
use Illuminate\Support\Collection;
use TPG\SMSPortal\Responses\BulkMessageResponse;

class MessageBuilder
{
    protected array|Collection|ArrayAccess|string $to;
    protected string $message;

    public function __construct(protected SMSPortal $client)
    {
    }

    public function to($to): self
    {
        $this->to = $to;
        return $this;
    }

    public function message(string $content): self
    {
        $this->message = $content;
        return $this;
    }

    public function send(?SendOptions $sendOptions = null): BulkMessageResponse
    {
        return $this->client->sendBulk($this->messages(), $sendOptions);
    }

    protected function messages(): Collection
    {
        if (is_array($this->to)) {
            return collect($this->to)->map(fn ($number) => new Message($number, $this->message));
        }

        return collect([new Message($this->to, $this->message)]);
    }
}
