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
        config('smsportal.endpoint').'/Balance' => Http::response([
            'balance' => 200,
        ])
    ]);

    $this->client = app(SMSPortalClient::class);
});

it('can get the current account balance', function () {

    $balance = $this->client->balance();

    $this->assertEquals(200, $balance);

});
