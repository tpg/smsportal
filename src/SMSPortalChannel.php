<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use Illuminate\Notifications\Notification;
use TPG\SMSPortal\Contracts\SMSPortalClient;

class SMSPortalChannel
{
    public function __construct(protected SMSPortalClient $client)
    {
    }

    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSMSPortal')) {
            throw new \RuntimeException('Notification does not have a toSMSPortal method.');
        }

        $message = $notification->toSMSPortal($notifiable);

        if (! $to = $notifiable->routeNotificationFor('smsportal', $notification)) {
            return;
        }

        if (is_string($message)) {
            $message = new Message($to, $message);
        }

        $response = $this->client->send($message);
    }
}
