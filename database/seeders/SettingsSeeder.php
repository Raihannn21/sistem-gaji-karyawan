<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key'         => 'gaji_harian',
                'label'       => 'Gaji Pokok Per Hari',
                'value'       => 100000.00,
                'description' => 'Nominal gaji yang diterima karyawan untuk setiap hari kehadiran',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_biasa',
                'label'       => 'Rate Lembur Hari Biasa (Per Jam)',
                'value'       => 20000.00,
                'description' => 'Nominal lembur per jam di hari kerja normal',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_libur',
                'label'       => 'Rate Lembur Hari Libur / Merah (Per Jam)',
                'value'       => 35000.00,
                'description' => 'Nominal lembur per jam di hari libur nasional / tanggal merah',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'jam_kerja_normal',
                'label'       => 'Jam Kerja Normal Per Shift (Jam)',
                'value'       => 8.00,
                'description' => 'Durasi jam kerja standar per shift sebelum dihitung lembur',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
