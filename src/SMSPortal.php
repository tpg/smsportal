<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use ArrayAccess;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use TPG\SMSPortal\Contracts\SMSPortalClient;
use TPG\SMSPortal\Enums\ResponseStatus;
use TPG\SMSPortal\Exceptions\AuthenticationException;
use TPG\SMSPortal\Exceptions\BalanceException;
use TPG\SMSPortal\Exceptions\SendException;
use TPG\SMSPortal\Responses\BulkMessageResponse;

class SMSPortal implements SMSPortalClient
{
    public function __construct(protected readonly array $config = [])
    {
    }

    /**
     * Get the current SMSPortal credit balance.
     *
     * @return float
     * @throws BalanceException
     */
    public function balance(): float
    {
        try {
            $response = Http::withHeaders($this->headers())->get($this->endpoint('Balance'));

            if ($response->status() !== 200) {
                throw new BalanceException();
            }

            return (float)$response->json('balance');

        } catch (Exception $exception) {
            throw new BalanceException($exception);
        }
    }

    /**
     * @param  Message  $message
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function send(Message $message, ?SendOptions $sendOptions = null): BulkMessageResponse
    {
        return $this->sendBulk(collect([$message]), $sendOptions);
    }

    /**
     * Send a message to an SMSPortal contact group.
     *
     * @param  string  $message
     * @param  array<string>  $groups
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function sendGroup(string $message, array $groups, ?SendOptions $sendOptions = null): BulkMessageResponse
    {
        return $this->sendMessages($this->endpoint('GroupMessages'), [
            'sendOptions' => $sendOptions?->toArray() ?? [],
            'message' => $message,
            'groups' => $groups,
        ]);
    }

    /**
     * @param  ArrayAccess<Message>  $messages
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function sendBulk(ArrayAccess $messages, ?SendOptions $sendOptions = null): BulkMessageResponse
    {
        if (! is_subclass_of($messages, Collection::class)) {
            $messages = collect($messages);
        }

        return $this->sendMessages($this->endpoint('BulkMessages'), [
            'sendOptions' => $sendOptions?->toArray() ?? [],
            'messages' => $messages->map(fn (Message $message) => $messages->toArray()),
        ]);
    }

    /**
     * Clear the stored auth token.
     *
     * @return void
     */
    public function clearToken(): void
    {
        Cache::forget($this->cacheKey());
    }

    protected function sendMessages(string $endpoint, array $data): BulkMessageResponse
    {
        try {
            $response = Http::withHeaders($this->headers())->post($endpoint, $data);

            if ($response->status() !== ResponseStatus::Ok->value) {
                throw new SendException(ResponseStatus::from($response->status())->presentable(), $response->status());
            }

            return new BulkMessageResponse(...$response->json());
        } catch (Exception $exception) {
            throw new SendException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    protected function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->token(),
        ];
    }

    /**
     * Authentication with SMSPortal and store the auth token.
     *
     * @return string
     */
    public function token(): string
    {
        $cacheDuration = Arr::get($this->config, 'token.duration', 43200);

        return Cache::remember($this->cacheKey(), now()->addSeconds($cacheDuration), function () {
            return $this->authenticate();
        });
    }

    /**
     * Authenticate with SMSPortal and get a new auth token.
     *
     * @return string
     * @throws AuthenticationException
     */
    public function authenticate(): string
    {
        try {
            $response = Http::acceptJson()->withHeaders([
                'Authorization' => 'Basic '.$this->credentials(),
            ])->get($this->endpoint('Authentication'));

            if ($response->status() !== 200) {
                throw new AuthenticationException($response->status());
            }

            return $response->json('token');

        } catch (Exception $exception) {
            throw new AuthenticationException($exception->getCode(), $exception->getPrevious());
        }
    }

    protected function cacheKey(): string
    {
        return 'smsportal-token';
    }

    protected function credentials(): string
    {
        return Arr::get($this->config, 'id').':'.Arr::get($this->config, 'secret');
    }

    protected function endpoint(string $uri = null): string
    {
        $base = Arr::get($this->config, 'endpoint');

        if (! Str::of($base)->endsWith('/') && ! Str::of($uri)->startsWith('/')) {
            $base .= '/';
        }

        return $base.$uri;
    }
}
