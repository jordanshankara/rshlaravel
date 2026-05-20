<?php

namespace App\Providers;

use App\Mail\CustomMailManager;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // MailServiceProvider is a DeferrableProvider — it only fires when
        // mail.manager is first resolved. Binding in register() gets overwritten.
        // extend() runs AFTER the deferred provider resolves, so it wins.
        $this->app->extend('mail.manager', fn ($manager, $app) => new CustomMailManager($app));
    }
}

