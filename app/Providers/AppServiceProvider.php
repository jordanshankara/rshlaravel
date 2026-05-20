<?php

namespace App\Providers;

use App\Mail\CustomMailManager;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mail.manager', fn ($app) => new CustomMailManager($app));
        $this->app->alias('mail.manager', MailManager::class);
    }

    public function boot(): void {}
}

