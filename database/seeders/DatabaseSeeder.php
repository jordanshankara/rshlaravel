<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'name'     => 'Administrator',
                'password' => Hash::make('admin123', ['rounds' => 12]),
                'role'     => 'ADMIN',
            ]);
        }

        // Default site settings
        $defaults = [
            'site_name'          => 'RSH Satu Bumi',
            'site_tagline'       => 'Program 7 Hari Menuju Sehat Raga & Jiwa',
            'site_address'       => '',
            'site_phone'         => '',
            'site_email'         => '',
            'whatsapp_number'    => '',
            'program_description'=> 'Sebuah perjalanan transformasi kesehatan yang holistik, menyelaraskan tubuh, pikiran, dan jiwa.',
            'bank_name'          => '',
            'bank_account_number'=> '',
            'bank_account_name'  => '',
            'google_maps_embed'  => '',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
