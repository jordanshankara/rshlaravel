<?php

namespace Database\Seeders;

use App\Models\InvoiceProduct;
use App\Models\PaymentDetail;
use Illuminate\Database\Seeder;

class InvoiceProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Sehat Dalam Sekejap',
                'category'    => 'Konsultasi',
                'description' => 'Program kesehatan cepat dan efektif',
                'price'       => 300000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Quantum Scan',
                'category'    => 'Pemeriksaan',
                'description' => 'Pemeriksaan kesehatan dengan teknologi quantum',
                'price'       => 180000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Program 7 Hari Menuju Sehat Raga & Jiwa',
                'category'    => 'Program',
                'description' => 'Program holistik 7 hari untuk kesehatan optimal',
                'price'       => 6300000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Divya Sparsh',
                'category'    => 'Terapi',
                'description' => 'Terapi sentuhan energi penyembuhan',
                'price'       => 250000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Terapi Holistik',
                'category'    => 'Terapi',
                'description' => 'Terapi kesehatan secara menyeluruh',
                'price'       => 350000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Konsultasi Kesehatan',
                'category'    => 'Konsultasi',
                'description' => 'Konsultasi langsung dengan ahli kesehatan',
                'price'       => 250000,
                'is_active'   => true,
            ],
        ];

        foreach ($products as $product) {
            InvoiceProduct::firstOrCreate(['name' => $product['name']], $product);
        }

        // Default payment detail
        if (!PaymentDetail::where('account_number', '5050096370')->exists()) {
            PaymentDetail::where('is_default', true)->update(['is_default' => false]);
            PaymentDetail::create([
                'bank_name'      => 'BCA',
                'account_number' => '5050096370',
                'account_name'   => 'Siti Rohmah',
                'is_default'     => true,
            ]);
        }
    }
}
