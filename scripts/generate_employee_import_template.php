<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Employee;
use Spatie\SimpleExcel\SimpleExcelWriter;

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
    $rows[] = [
        'emp_no' => 'EMP' . str_pad((string) (2000 + $i), 4, '0', STR_PAD_LEFT),
        'no_id' => 'ID' . str_pad((string) (3000 + $i), 4, '0', STR_PAD_LEFT),
        'nik' => '320100' . str_pad((string) (1000000000 + $i), 10, '0', STR_PAD_LEFT),
        'nama' => $firstNames[($i - 1) % count($firstNames)] . ' ' . $lastNames[($i - 1) % count($lastNames)],
        'employment_status' => $i % 2 === 0 ? Employee::STATUS_PKWT : Employee::STATUS_PHL,
        'email' => 'karyawan' . $i . '@example.com',
        'departemen' => $departments[($i - 1) % count($departments)],
        'no_hp' => '08' . str_pad((string) (1111000000 + $i), 10, '0', STR_PAD_LEFT),
        'is_active' => 1,
    ];
}

$targetDir = __DIR__ . '/../storage/app/import-templates';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$xlsxPath = $targetDir . '/employees_import_50.xlsx';
$csvPath = $targetDir . '/employees_import_50.csv';

$writerXlsx = SimpleExcelWriter::create($xlsxPath);
$writerCsv = SimpleExcelWriter::create($csvPath);

foreach ($rows as $row) {
    $writerXlsx->addRow($row);
    $writerCsv->addRow($row);
}

$writerXlsx->close();
$writerCsv->close();

echo "Generated:\n";
echo $xlsxPath . "\n";
echo $csvPath . "\n";
