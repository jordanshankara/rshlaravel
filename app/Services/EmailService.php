<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendRegistrationConfirmation(Registration $registration): void
    {
        $email = config('mail.from.address');
        if (!$email) return;

        try {
            $registration->load('programPeriod');
            Mail::send('emails.registration-confirmation', [
                'registration' => $registration,
                'period' => $registration->programPeriod,
            ], function ($message) use ($registration) {
                $message->to($registration->whatsapp . '@example.com') // placeholder — no email field
                        ->subject('Konfirmasi Pendaftaran Program - ' . $registration->registration_code);
            });
        } catch (\Throwable) {
            // Email is non-critical; log silently
        }
    }
}
