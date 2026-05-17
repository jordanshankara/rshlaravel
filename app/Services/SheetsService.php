<?php

namespace App\Services;

use App\Models\Registration;
use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class SheetsService
{
    private ?Sheets $service = null;
    private string $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google_sheets.spreadsheet_id', '');
    }

    private function getService(): ?Sheets
    {
        if ($this->service) return $this->service;

        $credentialsJson = config('services.google_sheets.credentials_json', '');
        if (!$credentialsJson || !$this->spreadsheetId) return null;

        try {
            $client = new GoogleClient();
            $credentials = json_decode($credentialsJson, true);
            $client->setAuthConfig($credentials);
            $client->setScopes([Sheets::SPREADSHEETS]);
            $this->service = new Sheets($client);
        } catch (\Throwable) {
            $this->service = null;
        }

        return $this->service;
    }

    private function ensureHeaders(Sheets $service): void
    {
        try {
            $response = $service->spreadsheets_values->get($this->spreadsheetId, 'Sheet1!A1:R1');
            $values = $response->getValues();
            if (!empty($values[0])) return;

            $headers = [
                'Kode Pendaftaran', 'Tanggal Daftar', 'Nama Lengkap', 'Tanggal Lahir',
                'Pekerjaan', 'WhatsApp', 'Alamat', 'Tinggi/Berat',
                'Periode Program', 'Status', 'Keluhan Kesehatan', 'Data Klinis',
                'BMI', 'Kondisi Emosi', 'Alergi Makanan', 'Riwayat Pengobatan',
                'Obat/Suplemen', 'Tingkat Keyakinan',
            ];
            $body = new ValueRange(['values' => [$headers]]);
            $service->spreadsheets_values->update(
                $this->spreadsheetId,
                'Sheet1!A1',
                $body,
                ['valueInputOption' => 'RAW']
            );
        } catch (\Throwable) {}
    }

    public function appendRegistration(Registration $registration): ?string
    {
        $service = $this->getService();
        if (!$service) return null;

        try {
            $this->ensureHeaders($service);
            $registration->load('programPeriod');
            $period = $registration->programPeriod;

            $row = [
                $registration->registration_code,
                $registration->submitted_at->format('Y-m-d H:i:s'),
                $registration->full_name,
                $registration->birth_date->format('Y-m-d'),
                $registration->occupation,
                $registration->whatsapp,
                $registration->address,
                $registration->height_weight,
                $period->name,
                $registration->status,
                $registration->health_complaints,
                $registration->clinical_details,
                (string) ($registration->bmi ?? ''),
                $registration->emotion_state,
                $registration->food_allergies,
                $registration->treatment_history,
                $registration->current_meds,
                (string) $registration->confidence_level,
            ];

            $body = new ValueRange(['values' => [$row]]);
            $result = $service->spreadsheets_values->append(
                $this->spreadsheetId,
                'Sheet1!A1',
                $body,
                ['valueInputOption' => 'RAW', 'insertDataOption' => 'INSERT_ROWS']
            );

            // Extract row ID from updatedRange (e.g. "Sheet1!A5:R5" → "5")
            $updatedRange = $result->getUpdates()?->getUpdatedRange() ?? '';
            preg_match('/!A(\d+)/', $updatedRange, $matches);
            return $matches[1] ?? null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function updateStatus(string $rowId, string $status): void
    {
        $service = $this->getService();
        if (!$service || !$rowId) return;

        try {
            $rowId = (string)(int)$rowId;
            $range = "Sheet1!J{$rowId}";
            $body = new ValueRange(['values' => [[$status]]]);
            $service->spreadsheets_values->update(
                $this->spreadsheetId,
                $range,
                $body,
                ['valueInputOption' => 'RAW']
            );
        } catch (\Throwable) {
            // Non-critical
        }
    }
}
