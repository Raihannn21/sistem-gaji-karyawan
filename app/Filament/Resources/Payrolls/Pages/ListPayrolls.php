<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\ManualOvertime;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Setting;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;
    protected string $view = 'filament.resources.payrolls.pages.list-payrolls';

    public ?string $statusFilter = null;

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
                    $periodeSudahAda = Payroll::query()
                        ->whereDate('tanggal_mulai', $data['tanggal_mulai'])
                        ->whereDate('tanggal_selesai', $data['tanggal_selesai'])
                        ->exists();

                    if ($periodeSudahAda) {
                        Notification::make()
                            ->title('Periode Sudah Ada')
                            ->body('Payroll untuk rentang tanggal ini sudah pernah dibuat. Gunakan data yang sudah ada atau hapus terlebih dahulu bila ingin generate ulang.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $activeEmployees = Employee::query()->where('is_active', true)->get();

                    $invalidStatusEmployees = $activeEmployees
                        ->filter(fn (Employee $employee) => !in_array($employee->employment_status, [Employee::STATUS_PHL, Employee::STATUS_PKWT], true));

                    if ($invalidStatusEmployees->isNotEmpty()) {
                        Notification::make()
                            ->title('Generate Dibatalkan')
                            ->body('Masih ada karyawan aktif tanpa status kerja valid (PHL/PKWT). Perbaiki data karyawan terlebih dahulu.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $rateByStatus = [
                        Employee::STATUS_PHL => [
                            'gaji_harian' => (float) (Setting::query()->where('key', Setting::KEY_GAJI_HARIAN_PHL)->value('value') ?? 100000),
                            'rate_biasa' => (float) (Setting::query()->where('key', Setting::KEY_RATE_LEMBUR_BIASA_PHL)->value('value') ?? 20000),
                            'rate_libur' => (float) (Setting::query()->where('key', Setting::KEY_RATE_LEMBUR_LIBUR_PHL)->value('value') ?? 35000),
                        ],
                        Employee::STATUS_PKWT => [
                            'gaji_harian' => (float) (Setting::query()->where('key', Setting::KEY_GAJI_HARIAN_PKWT)->value('value') ?? 125000),
                            'rate_biasa' => (float) (Setting::query()->where('key', Setting::KEY_RATE_LEMBUR_BIASA_PKWT)->value('value') ?? 25000),
                            'rate_libur' => (float) (Setting::query()->where('key', Setting::KEY_RATE_LEMBUR_LIBUR_PKWT)->value('value') ?? 40000),
                        ],
                    ];

                    $jumlahKaryawanDiproses = DB::transaction(function () use ($data, $activeEmployees, $rateByStatus) {
                        $payroll = Payroll::create([
                            'periode' => $data['periode'],
                            'tanggal_mulai' => $data['tanggal_mulai'],
                            'tanggal_selesai' => $data['tanggal_selesai'],
                            'status_payroll' => Payroll::STATUS_DRAFT,
                            'total_gaji_pokok' => 0,
                            'total_uang_lembur' => 0,
                            'grand_total' => 0,
                        ]);

                        $totalGajiPokokAll = 0;
                        $totalUangLemburAll = 0;

                        foreach ($activeEmployees as $employee) {
                            /** @var Employee $employee */
                            $status = (string) $employee->employment_status;
                            $rates = $rateByStatus[$status];

                            $totalHadir = $employee->attendances()
                                ->whereBetween('tanggal', [$data['tanggal_mulai'], $data['tanggal_selesai']])
                                ->where('is_holiday', false)
                                ->where('total_jam_kerja', '>', 0)
                                ->count();

                            $manualOvertime = $employee->manualOvertimes()
                                ->whereBetween('tanggal', [$data['tanggal_mulai'], $data['tanggal_selesai']])
                                ->selectRaw('jenis_lembur, SUM(jam_lembur) as total_jam')
                                ->groupBy('jenis_lembur')
                                ->pluck('total_jam', 'jenis_lembur');

                            $jamLemburBiasa = (int) ($manualOvertime[ManualOvertime::JENIS_BIASA] ?? 0);
                            $jamLemburLibur = (int) ($manualOvertime[ManualOvertime::JENIS_LIBUR] ?? 0);

                            $totalGajiKehadiran = $totalHadir * $rates['gaji_harian'];
                            $totalGajiLemburBiasa = $jamLemburBiasa * $rates['rate_biasa'];
                            $totalGajiLemburLibur = $jamLemburLibur * $rates['rate_libur'];
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

                        return $activeEmployees->count();
                    });

                    Notification::make()
                        ->title("Generate Berhasil!")
                        ->body("Rekap gaji untuk {$jumlahKaryawanDiproses} karyawan telah diverifikasi dan diproses.")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }

    public function finalizePayrollRecord(int $recordId): void
    {
        $record = Payroll::query()->find($recordId);

        if (!$record) {
            Notification::make()->title('Data payroll tidak ditemukan')->danger()->send();
            return;
        }

        if ($record->isFinalized()) {
            Notification::make()->title('Payroll sudah final')->warning()->send();
            return;
        }

        $record->update([
            'status_payroll' => Payroll::STATUS_FINAL,
            'finalized_at' => now(),
            'finalized_by' => Auth::id(),
        ]);

        Notification::make()
            ->title('Payroll Difinalisasi')
            ->body('Periode payroll telah dikunci.')
            ->success()
            ->send();
    }

    public function reopenPayrollRecord(int $recordId): void
    {
        $record = Payroll::query()->find($recordId);

        if (!$record) {
            Notification::make()->title('Data payroll tidak ditemukan')->danger()->send();
            return;
        }

        if (!$record->isFinalized()) {
            Notification::make()->title('Payroll masih draft')->warning()->send();
            return;
        }

        $record->update([
            'status_payroll' => Payroll::STATUS_DRAFT,
            'finalized_at' => null,
            'finalized_by' => null,
        ]);

        Notification::make()
            ->title('Finalisasi Dibuka')
            ->body('Periode payroll kembali dapat diedit.')
            ->success()
            ->send();
    }

    public function deletePayrollRecord(int $recordId): void
    {
        $record = Payroll::query()->find($recordId);

        if (!$record) {
            Notification::make()->title('Data payroll tidak ditemukan')->danger()->send();
            return;
        }

        if ($record->isFinalized()) {
            Notification::make()
                ->title('Gagal Menghapus')
                ->body('Payroll yang sudah final harus dibuka finalisasinya terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        $record->delete();

        Notification::make()
            ->title('Payroll Dihapus')
            ->success()
            ->send();
    }
}
