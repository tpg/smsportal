<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
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
            'schema' => 'JWT',
            'expiresInMinutes' => config('smsportal.token.duration'),
        ], 200, [
            'Content-Type' => 'application/json',
        ])
    ]);

    $this->client = app(SMSPortalClient::class);
});

it('can get a new auth token', function () {

    $token = $this->client->authenticate();

    $this->assertSame('TEST-TOKEN', $token);
});

it('will cache the returned auth token', function () {

    $token = $this->client->token();

    $this->assertTrue(Cache::has('smsportal-token'));
    $this->assertEquals('TEST-TOKEN', Cache::get('smsportal-token'));

    $token = $this->client->token();

    Http::assertSentCount(1);
});
