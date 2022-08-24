<?php

declare(strict_types=1);

namespace TPG\SMSPortal\Facades;

use Illuminate\Support\Facades\Facade;

class SMSPortal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tpg.smsportal.facade';
    }
}
