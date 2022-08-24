<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use TPG\SMSPortal\Contracts\SMSPortalClient;

beforeEach(function () {
    config([
        'smsportal.id' => 'SMSPORTAL-ID',
        'smsportal.secret' => 'SMSPORTAL-SECRET',
    ]);

    Http::fake([
        config('smsportal.endpoint').'/Authentication' => Http::response([
            'token' => 'TEST-TOKEN',
        ]),
        config('smsportal.endpoint').'/BulkMessages' => Http::response([
            "cost" => 10,
            "remainingBalance" => 10,
            "eventId" => 10,
            "sample" => "sample",
            "costBreakdown" => [
                [
                    "quantity" => 10,
                    "cost" => 10,
                    "network" => "network",
                ],
            ],
            "messages" => 10,
            "parts" => 10,
            "errorReport" => [
                "noNetwork" => 10,
                "noContents" => 10,
                "contentToLong" => 10,
                "duplicates" => 10,
                "optedOuts" => 10,
                "faults" => [
                    [
                        "rawDestination" => "rawDestination",
                        "scrubbedDestination" => "scrubbedDestination",
                        "customerId" => "customerId",
                        "errorMessage" => "errorMessage",
                        "status" => "status",
                    ],
                ],
            ],
        ], 200, [
            'Content-Type' => 'application/json',
        ])
    ]);

    $this->client = app(SMSPortalClient::class);
});

it('can send a message', function () {

    $message = new \TPG\SMSPortal\Message('27821234567', 'This is my message');

    /** @var \TPG\SMSPortal\Responses\BulkMessageResponse $response */
    $response = $this->client->send($message);

    $this->assertEquals(10, $response->messages);
    $this->assertEquals('rawDestination', $response->error()->faults()[0]->rawDestination);
});
