<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    protected string $view = 'filament.resources.employees.pages.list-employees';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importEmployees')
                ->label('Import Karyawan')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('File Excel / CSV')
                        ->disk('local')
                        ->directory('employee-imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('local')->path($data['attachment']);
                    $rows = SimpleExcelReader::create($path)->getRows();

                    $importedCount = 0;
                    $updatedCount = 0;

                    $rows->each(function (array $row) use (&$importedCount, &$updatedCount): void {
                        $normalizedRow = self::normalizeRowKeys($row);

                        $empNo = self::getFirstNotEmptyValue($normalizedRow, ['emp_no', 'empno', 'emp_number', 'employee_no']);
                        $noId = self::getFirstNotEmptyValue($normalizedRow, ['no_id', 'noid', 'id_karyawan']);
                        $nama = self::getFirstNotEmptyValue($normalizedRow, ['nama', 'name']);

                        if (!$empNo || !$noId || !$nama) {
                            return;
                        }

                        $payload = [
                            'emp_no' => (string) $empNo,
                            'no_id' => (string) $noId,
                            'nama' => (string) $nama,
                            'nik' => self::nullableString(self::getFirstNotEmptyValue($normalizedRow, ['nik'])),
                            'email' => self::nullableString(self::getFirstNotEmptyValue($normalizedRow, ['email', 'email_address'])),
                            'departemen' => self::nullableString(self::getFirstNotEmptyValue($normalizedRow, ['departemen', 'department', 'dept'])),
                            'no_hp' => self::nullableString(self::getFirstNotEmptyValue($normalizedRow, ['no_hp', 'nohp', 'phone', 'phone_number'])),
                            'is_active' => self::toBoolean(self::getFirstNotEmptyValue($normalizedRow, ['is_active', 'active', 'status']), true),
                        ];

                        $employee = Employee::query()->where('no_id', $payload['no_id'])->first();

                        if ($employee) {
                            $employee->update($payload);
                            $updatedCount++;
                            return;
                        }

                        Employee::create($payload);
                        $importedCount++;
                    });

                    Notification::make()
                        ->title('Import Karyawan Selesai')
                        ->body("{$importedCount} data baru ditambahkan, {$updatedCount} data diperbarui.")
                        ->success()
                        ->send();
                }),

            Action::make('exportEmployees')
                ->label('Export Karyawan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(function () {
                    $rows = Employee::query()
                        ->orderBy('nama')
                        ->get([
                            'emp_no',
                            'no_id',
                            'nik',
                            'nama',
                            'email',
                            'departemen',
                            'no_hp',
                            'is_active',
                        ]);

                    $filename = 'employees_export_' . now()->format('Ymd_His') . '.xlsx';
                    $tempPath = storage_path('app/temp/' . $filename);

                    if (!is_dir(dirname($tempPath))) {
                        mkdir(dirname($tempPath), 0755, true);
                    }

                    $writer = SimpleExcelWriter::create($tempPath);

                    foreach ($rows as $employee) {
                        $writer->addRow([
                            'emp_no' => $employee->emp_no,
                            'no_id' => $employee->no_id,
                            'nik' => $employee->nik,
                            'nama' => $employee->nama,
                            'email' => $employee->email,
                            'departemen' => $employee->departemen,
                            'no_hp' => $employee->no_hp,
                            'is_active' => $employee->is_active ? 1 : 0,
                        ]);
                    }

                    $writer->close();

                    return response()->download($tempPath, $filename, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])->deleteFileAfterSend(true);
                }),

            CreateAction::make(),
        ];
    }

    private static function normalizeRowKeys(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalizedKey = Str::of((string) $key)
                ->lower()
                ->replace(['.', '-', ' ', '/'], '_')
                ->replaceMatches('/_+/', '_')
                ->trim('_')
                ->value();

            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    private static function getFirstNotEmptyValue(array $row, array $possibleKeys): mixed
    {
        foreach ($possibleKeys as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }

            $value = $row[$key];

            if ($value === null) {
                continue;
            }

            if (is_string($value) && trim($value) === '') {
                continue;
            }

            return $value;
        }

        return null;
    }

    private static function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $stringValue = trim((string) $value);

        return $stringValue === '' ? null : $stringValue;
    }

    private static function toBoolean(mixed $value, bool $default): bool
    {
        if ($value === null) {
            return $default;
        }

        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['1', 'true', 'yes', 'ya', 'aktif', 'active'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'tidak', 'nonaktif', 'inactive'], true)) {
            return false;
        }

        return $default;
    }
}
