<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;
    protected string $view = 'filament.resources.attendances.pages.list-attendances';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importCsv')
                ->label('Import CSV / Excel Kehadiran')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('attachment')
                        ->label('File CSV / Excel (.xlsx) Fingerprint')
                        ->disk('local')
                        ->directory('csv-imports')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $path = Storage::disk('local')->path($data['attachment']);
                    $jamKerjaNormal = (float) (Setting::query()->where('key', Setting::KEY_JAM_KERJA_NORMAL)->value('value') ?? 8);
                    
                    // Membaca CSV / Excel menggunakan SimpleExcel
                    $rows = SimpleExcelReader::create($path)->getRows();
                    
                    $importCount = 0;
                    $unknownEmployeeCount = 0;
                    $invalidStatusCount = 0;
                    
                    $rows->each(function(array $row) use (&$importCount, &$unknownEmployeeCount, &$invalidStatusCount, $jamKerjaNormal) {
                        try {
                            $noId = $row['No. ID'] ?? null;
                            $tanggalStr = $row['Tanggal'] ?? null;
                            
                            // Abaikan row kosong / header salah
                            if (!$noId || !$tanggalStr) return;

                            // 1. Karyawan wajib sudah terdaftar agar status kerja tervalidasi.
                            $employee = Employee::query()->where('no_id', (string) $noId)->first();

                            if (!$employee) {
                                $unknownEmployeeCount++;
                                return;
                            }

                            if (!in_array($employee->employment_status, [Employee::STATUS_PHL, Employee::STATUS_PKWT], true)) {
                                $invalidStatusCount++;
                                return;
                            }

                            // 2. Format Tanggal dari DD/MM/YYYY
                            $tanggal = Carbon::createFromFormat('d/m/Y', $tanggalStr)->format('Y-m-d');
                            
                            // 3. Mengambil Scan Masuk & Pulang
                            $scanMasuk = !empty($row['Scan Masuk']) ? $row['Scan Masuk'] : null;
                            $scanPulang = !empty($row['Scan Pulang']) ? $row['Scan Pulang'] : null;
                            
                            // Jika sama sekali tidak ada scan masuk dan pulang (Tidak Valid / Bolos), skip data ini
                            if (!$scanMasuk && !$scanPulang) {
                                return;
                            }
                            
                            // 4. Cek apakah ini Hari Libur Nasional / Minggu
                            $isSunday = Carbon::parse($tanggal)->isSunday();
                            $isHolidayDb = Holiday::where('tanggal', $tanggal)->exists();
                            $isHoliday = $isSunday || $isHolidayDb;

                            // 5. Kalkulasi Total Jam Kerja
                            $totalJam = 0;
                            if ($scanMasuk && $scanPulang) {
                                // Hitung selisih jam akurat
                                $awal = Carbon::createFromFormat('H:i', $scanMasuk);
                                $akhir = Carbon::createFromFormat('H:i', $scanPulang);
                                
                                // Antisipasi kalau pulang setelah tengah malam / beda hari
                                if ($akhir->lessThan($awal)) {
                                    $akhir->addDay();
                                }
                                
                                // Menjadikan jam desimal (contoh: 8 jam 30 menit = 8.5)
                                $totalJam = $awal->diffInMinutes($akhir) / 60;
                            }

                            $totalJam = min($totalJam, $jamKerjaNormal);

                            // 6. Simpan Data Kehadiran (Upsert agar aman jika import 2x)
                            Attendance::updateOrCreate(
                                ['employee_id' => $employee->id, 'tanggal' => $tanggal],
                                [
                                    'scan_masuk' => $scanMasuk,
                                    'scan_pulang' => $scanPulang,
                                    'total_jam_kerja' => $totalJam,
                                    'is_holiday' => $isHoliday,
                                ]
                            );

                            $importCount++;
                        } catch (\Exception $e) {
                            // Abaikan / log error per row jika perlu
                        }
                    });

                    Notification::make()
                        ->title("Import Berhasil!")
                        ->body("{$importCount} data tersimpan, {$unknownEmployeeCount} dilewati (karyawan belum terdaftar), {$invalidStatusCount} dilewati (status kerja tidak valid).")
                        ->success()
                        ->send();
                }),
        ];
    }
}

