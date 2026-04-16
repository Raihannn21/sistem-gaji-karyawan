<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSampleSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Produksi',
            'Gudang',
            'Finishing',
            'Packing',
            'Quality Control',
            'Admin',
        ];

        $firstNames = [
            'Ahmad', 'Budi', 'Cahya', 'Deni', 'Eko', 'Fajar', 'Galih', 'Hendra', 'Indra', 'Joko',
            'Kurnia', 'Lukman', 'Maman', 'Nanda', 'Oki', 'Pandu', 'Rian', 'Sandi', 'Taufik', 'Wawan',
            'Yudi', 'Zaki', 'Ayu', 'Bella', 'Citra', 'Dewi', 'Euis', 'Fitri', 'Gita', 'Hani',
            'Intan', 'Jihan', 'Kiki', 'Lina', 'Maya', 'Nia', 'Olivia', 'Putri', 'Rani', 'Salsa',
            'Tia', 'Ulfa', 'Vina', 'Winda', 'Yuni', 'Zahra', 'Anisa', 'Bunga', 'Cindy', 'Dinda',
        ];

        $lastNames = [
            'Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Nugraha',
            'Lestari', 'Permata', 'Sari', 'Ramadhan', 'Firmansyah',
        ];

        $rows = [];

        for ($i = 1; $i <= 50; $i++) {
            $empNo = 'EMP' . str_pad((string) (2000 + $i), 4, '0', STR_PAD_LEFT);
            $noId = 'ID' . str_pad((string) (3000 + $i), 4, '0', STR_PAD_LEFT);
            $nik = '320100' . str_pad((string) (1000000000 + $i), 10, '0', STR_PAD_LEFT);

            $firstName = $firstNames[($i - 1) % count($firstNames)];
            $lastName = $lastNames[($i - 1) % count($lastNames)];
            $name = $firstName . ' ' . $lastName;

            $rows[] = [
                'emp_no' => $empNo,
                'no_id' => $noId,
                'nik' => $nik,
                'nama' => $name,
                'employment_status' => $i % 2 === 0 ? Employee::STATUS_PKWT : Employee::STATUS_PHL,
                'email' => 'karyawan' . $i . '@example.com',
                'departemen' => $departments[($i - 1) % count($departments)],
                'no_hp' => '08' . str_pad((string) (1111000000 + $i), 10, '0', STR_PAD_LEFT),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Employee::query()->upsert(
            $rows,
            ['no_id'],
            ['emp_no', 'nik', 'nama', 'employment_status', 'email', 'departemen', 'no_hp', 'is_active', 'updated_at']
        );
    }
}
