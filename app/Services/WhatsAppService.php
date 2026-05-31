<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Service — stub implementation.
 *
 * All methods are no-ops and return false until a real
 * provider (Fonnte, Meta Cloud API, etc.) is connected.
 * To activate: replace the bodies of sendText() and getConnectionStatus()
 * without changing any method signatures — everything else will work automatically.
 */
class WhatsAppService
{
    public function sendText(string $phone, string $message): bool
    {
        Log::info('[WhatsApp stub] Would send to ' . $phone . ': ' . substr($message, 0, 80));
        return false;
    }

    public function sendMonitoringLink(Registration $registration, int $dayNumber, string $link): bool
    {
        $name    = $registration->full_name;
        $message = "Halo {$name}, 🙏\n\nIni link Energy Level hari ke-{$dayNumber} program Rumah Sehat Satu Bumi:\n{$link}\n\nSilakan isi dengan jujur. Terima kasih! 🙏";
        return $this->sendText($registration->whatsapp, $message);
    }

    /** Returns 'connected' | 'disconnected' | 'not_configured' */
    public function getConnectionStatus(): string
    {
        return 'not_configured';
    }
}
