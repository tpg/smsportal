<?php

declare(strict_types=1);

namespace TPG\SMSPortal;

use Illuminate\Support\ServiceProvider;
use TPG\SMSPortal\Contracts\SMSPortalClient;

class SMSPortalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/smsportal.php' => config_path('smsportal.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/smsportal.php', 'smsportal');

        $this->app->bind(SMSPortalClient::class, function () {
            $config = config('smsportal') ?? config('services.smsportal');
            return new SMSPortal($config);
        });

        $this->app->bind('tpg.smsportal.facade', fn () => app(SMSPortalClient::class));
    }
}
