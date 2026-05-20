<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Shared hosting (Hostwhitelabel): SSL cert CN is the physical server name,
        // not the custom MAIL_HOST domain — peer verification must be disabled.
        // This runs on every request and is not affected by config cache.
        $this->app->resolving('mailer', function ($mailer) {
            $transport = $mailer->getSymfonyTransport();
            if ($transport instanceof EsmtpTransport) {
                $transport->setStreamOptions([
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                    ],
                ]);
            }
        });
    }
}

