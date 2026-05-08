<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'bank_sampah_name',
                'value' => 'Bank Sampah Mandiri',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama Bank Sampah',
                'is_public' => true,
            ],
            [
                'key' => 'bank_sampah_address',
                'value' => 'Jl. Contoh No. 123, Yogyakarta',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Alamat Bank Sampah',
                'is_public' => true,
            ],
            [
                'key' => 'bank_sampah_phone',
                'value' => '0274-123456',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nomor Telepon Bank Sampah',
                'is_public' => true,
            ],
            [
                'key' => 'bank_sampah_email',
                'value' => 'info@banksampah.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Email Bank Sampah',
                'is_public' => true,
            ],
            [
                'key' => 'default_sedekah_percentage',
                'value' => '50',
                'type' => 'number',
                'group' => 'sedekah',
                'description' => 'Persentase default untuk sedekah sampah',
                'is_public' => false,
            ],
            [
                'key' => 'kas_allocation_percentage',
                'value' => '50',
                'type' => 'number',
                'group' => 'kas',
                'description' => 'Persentase alokasi ke kas dari sedekah',
                'is_public' => false,
            ],
            [
                'key' => 'notification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi sistem',
                'is_public' => false,
            ],
            [
                'key' => 'operating_hours',
                'value' => '{"senin": "08:00-16:00", "selasa": "08:00-16:00", "rabu": "08:00-16:00", "kamis": "08:00-16:00", "jumat": "08:00-16:00", "sabtu": "08:00-12:00", "minggu": "tutup"}',
                'type' => 'json',
                'group' => 'general',
                'description' => 'Jam operasional bank sampah',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
