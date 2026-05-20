<?php

namespace App\Mail;

use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * Overrides configureSmtpTransport() to disable SSL peer verification.
 *
 * Hostwhitelabel shared hosting serves a cert for the physical server name
 * (server20.hostwhitelabel.com), not the custom MAIL_HOST domain. PHP rejects
 * the TLS handshake due to CN mismatch unless peer verification is disabled.
 *
 * Why subclass? In Laravel 10 the stream config key in mail.php is silently
 * ignored, and resolving('mailer') never fires because MailManager creates
 * Mailer instances with `new Mailer(...)` directly (not via container).
 * Overriding configureSmtpTransport() is the only reliable hook.
 */
class CustomMailManager extends MailManager
{
    protected function configureSmtpTransport(EsmtpTransport $transport, array $config): EsmtpTransport
    {
        parent::configureSmtpTransport($transport, $config);

        $transport->getStream()->setStreamOptions([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ],
        ]);

        return $transport;
    }
}
