<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Contracts;

use ArrayAccess;
use TPG\SMSPortal\Exceptions\AuthenticationException;
use TPG\SMSPortal\Exceptions\BalanceException;
use TPG\SMSPortal\Exceptions\SendException;
use TPG\SMSPortal\Message;
use TPG\SMSPortal\Responses\BulkMessageResponse;
use TPG\SMSPortal\SendOptions;

interface SMSPortalClient
{
    /**
     * Get the current SMSPortal credit balance.
     *
     * @return float
     * @throws BalanceException
     */
    public function balance(): float;

    /**
     * @param  Message  $message
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function send(Message $message, ?SendOptions $sendOptions = null): BulkMessageResponse;

    /**
     * Send a message to an SMSPortal contact group.
     *
     * @param  string  $message
     * @param  array<string>  $groups
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function sendGroup(string $message, array $groups, ?SendOptions $sendOptions = null): BulkMessageResponse;

    /**
     * @param  ArrayAccess<Message>  $messages
     * @param  SendOptions|null  $sendOptions
     * @return BulkMessageResponse
     * @throws SendException
     */
    public function sendBulk(ArrayAccess $messages, ?SendOptions $sendOptions = null): BulkMessageResponse;

    /**
     * Clear the stored auth token.
     *
     * @return void
     */
    public function clearToken(): void;

    /**
     * Authentication with SMSPortal and store the auth token.
     *
     * @return string
     */
    public function token(): string;

    /**
     * Authenticate with SMSPortal and get a new auth token.
     *
     * @return string
     * @throws AuthenticationException
     */
    public function authenticate(): string;
}
