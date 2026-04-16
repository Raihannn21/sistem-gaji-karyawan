<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Employee;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$employees = Employee::query()
    ->whereIn('employment_status', [Employee::STATUS_PHL, Employee::STATUS_PKWT])
    ->where('is_active', true)
    ->orderBy('no_id')
    ->limit(50)
    ->get(['id', 'no_id', 'nama', 'employment_status']);

if ($employees->isEmpty()) {
    echo "No active employees with PHL/PKWT status found.\n";
    exit(1);
}

$startDate = Carbon::create(2026, 4, 1);
$endDate = Carbon::create(2026, 4, 15);

$rows = [];
$current = $startDate->copy();

while ($current->lte($endDate)) {
    if (!$current->isSunday()) {
        foreach ($employees as $index => $employee) {
            // Deterministic pattern: small variation in check-in/check-out for realistic samples.
            $offsetIn = ($index % 4) * 5;      // 0, 5, 10, 15 minutes
            $offsetOut = ($index % 3) * 10;    // 0, 10, 20 minutes

            $scanIn = Carbon::createFromTime(8, 0)->addMinutes($offsetIn)->format('H:i');
            $scanOut = Carbon::createFromTime(17, 0)->subMinutes($offsetOut)->format('H:i');

            $rows[] = [
                'No. ID' => (string) $employee->no_id,
                'Tanggal' => $current->format('d/m/Y'),
                'Scan Masuk' => $scanIn,
                'Scan Pulang' => $scanOut,
            ];
        }
    }

    $current->addDay();
}

$targetDir = __DIR__ . '/../storage/app/import-templates';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$csvPath = $targetDir . '/attendances_import_2026-04-01_to_2026-04-15.csv';
$xlsxPath = $targetDir . '/attendances_import_2026-04-01_to_2026-04-15.xlsx';

$csvWriter = SimpleExcelWriter::create($csvPath);
$xlsxWriter = SimpleExcelWriter::create($xlsxPath);

foreach ($rows as $row) {
    $csvWriter->addRow($row);
    $xlsxWriter->addRow($row);
}

$csvWriter->close();
$xlsxWriter->close();

echo "Generated files:\n";
echo $csvPath . "\n";
echo $xlsxPath . "\n";
echo "Employees: " . $employees->count() . "\n";
echo "Rows: " . count($rows) . "\n";
