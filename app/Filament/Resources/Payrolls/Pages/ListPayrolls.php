<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Setting;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;
    protected string $view = 'filament.resources.payrolls.pages.list-payrolls';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generatePayroll')
                ->label('Generate Rekap Gaji')
                ->color('primary')
                ->icon('heroicon-o-calculator')
                ->form([
                    TextInput::make('periode')
                        ->label('Nama Periode (Contoh: Februari 2026)')
                        ->required(),
                    DatePicker::make('tanggal_mulai')
                        ->label('Dari Tanggal')
                        ->required()
                        ->default(now()->subMonth()->startOfMonth()),
                    DatePicker::make('tanggal_selesai')
                        ->label('Sampai Tanggal')
                        ->required()
                        ->default(now()->endOfMonth()),
                ])
                ->action(function (array $data) {
                    $gajiHarian = Setting::where('key', 'gaji_harian')->value('value') ?? 100000;
                    $rateLemburBiasa = Setting::where('key', 'rate_lembur_biasa')->value('value') ?? 20000;
                    $rateLemburLibur = Setting::where('key', 'rate_lembur_libur')->value('value') ?? 35000;
                    $jamKerjaNormal = Setting::where('key', 'jam_kerja_normal')->value('value') ?? 8;

                    $payroll = Payroll::create([
                        'periode' => $data['periode'],
                        'tanggal_mulai' => $data['tanggal_mulai'],
                        'tanggal_selesai' => $data['tanggal_selesai'],
                        'total_gaji_pokok' => 0,
                        'total_uang_lembur' => 0,
                        'grand_total' => 0,
                    ]);

                    $employees = Employee::with([
                        'attendances' => function ($query) use ($data) {
                            $query->whereBetween('tanggal', [$data['tanggal_mulai'], $data['tanggal_selesai']]);
                        }
                    ])->where('is_active', true)->get();

                    $totalGajiPokokAll = 0;
                    $totalUangLemburAll = 0;

                    foreach ($employees as $employee) {
                        $totalHadir = 0;
                        $jamLemburBiasa = 0;
                        $jamLemburLibur = 0;

                        foreach ($employee->attendances as $att) {
                            $totalKerja = (float) $att->total_jam_kerja; // Dalam desimal jam
                            $scanKosong = is_null($att->scan_masuk) || is_null($att->scan_pulang);

                            if ($att->is_holiday) {
                                // Hari libur: Seluruh jam kerja dihitung lembur libur (floor per 60 menit / jam)
                                $jamLemburLibur += floor($totalKerja);
                            } else {
                                // Hari kerja normnal
                                $totalHadir++;
                                if (!$scanKosong && $totalKerja > $jamKerjaNormal) {
                                    $jamLemburBiasa += floor($totalKerja - $jamKerjaNormal);
                                }
                            }
                        }

                        $totalGajiKehadiran = $totalHadir * $gajiHarian;
                        $totalGajiLemburBiasa = $jamLemburBiasa * $rateLemburBiasa;
                        $totalGajiLemburLibur = $jamLemburLibur * $rateLemburLibur;
                        $totalKaryawanGaji = $totalGajiKehadiran + $totalGajiLemburBiasa + $totalGajiLemburLibur;

                        $payroll->details()->create([
                            'employee_id' => $employee->id,
                            'total_hadir' => $totalHadir,
                            'total_gaji_kehadiran' => $totalGajiKehadiran,
                            'jam_lembur_biasa' => $jamLemburBiasa,
                            'total_gaji_lembur_biasa' => $totalGajiLemburBiasa,
                            'jam_lembur_libur' => $jamLemburLibur,
                            'total_gaji_lembur_libur' => $totalGajiLemburLibur,
                            'total_gaji' => $totalKaryawanGaji,
                        ]);

                        $totalGajiPokokAll += $totalGajiKehadiran;
                        $totalUangLemburAll += ($totalGajiLemburBiasa + $totalGajiLemburLibur);
                    }

                    $payroll->update([
                        'total_gaji_pokok' => $totalGajiPokokAll,
                        'total_uang_lembur' => $totalUangLemburAll,
                        'grand_total' => $totalGajiPokokAll + $totalUangLemburAll,
                    ]);

                    Notification::make()
                        ->title("Generate Berhasil!")
                        ->body("Rekap gaji untuk {$employees->count()} karyawan telah diverifikasi dan diproses.")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make('delete'),
        ];
    }
}
