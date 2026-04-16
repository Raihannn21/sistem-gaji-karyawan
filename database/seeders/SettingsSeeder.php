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
                'key'         => 'gaji_harian_phl',
                'label'       => 'Gaji Pokok PHL Per Hari',
                'value'       => 100000.00,
                'description' => 'Nominal gaji pokok harian untuk karyawan berstatus PHL',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'gaji_harian_pkwt',
                'label'       => 'Gaji Pokok PKWT Per Hari',
                'value'       => 125000.00,
                'description' => 'Nominal gaji pokok harian untuk karyawan berstatus PKWT',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_biasa_phl',
                'label'       => 'Rate Lembur Hari Biasa PHL (Per Jam)',
                'value'       => 20000.00,
                'description' => 'Nominal lembur per jam di hari kerja normal untuk status PHL',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_biasa_pkwt',
                'label'       => 'Rate Lembur Hari Biasa PKWT (Per Jam)',
                'value'       => 25000.00,
                'description' => 'Nominal lembur per jam di hari kerja normal untuk status PKWT',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_libur_phl',
                'label'       => 'Rate Lembur Hari Libur PHL (Per Jam)',
                'value'       => 35000.00,
                'description' => 'Nominal lembur per jam di hari libur nasional untuk status PHL',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'rate_lembur_libur_pkwt',
                'label'       => 'Rate Lembur Hari Libur PKWT (Per Jam)',
                'value'       => 40000.00,
                'description' => 'Nominal lembur per jam di hari libur nasional untuk status PKWT',
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
