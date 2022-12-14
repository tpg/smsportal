<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TPG\SMSPortal\SMSPortalServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SMSPortalServiceProvider::class,
        ];
    }
}
