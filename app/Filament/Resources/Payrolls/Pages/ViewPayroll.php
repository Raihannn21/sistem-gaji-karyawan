<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;
    protected string $view = 'filament.resources.payrolls.pages.view-payroll';

    public ?string $tableSearch = null;
    public ?string $statusFilter = null;

    protected function getActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(function () {
                    $details = $this->getFilteredDetails();

                    if ($details->isEmpty()) {
                        Notification::make()->title('Tidak ada data untuk diexport')->warning()->send();
                        return null;
                    }

                    $filename = 'payroll_' . $this->record->id . '_' . now()->format('Ymd_His') . '.xlsx';
                    $tempPath = storage_path('app/temp/' . $filename);

                    if (!is_dir(dirname($tempPath))) {
                        mkdir(dirname($tempPath), 0755, true);
                    }

                    $writer = SimpleExcelWriter::create($tempPath);

                    foreach ($details as $detail) {
                        $writer->addRow([
                            'periode' => $this->record->periode,
                            'tanggal_mulai' => $this->record->tanggal_mulai,
                            'tanggal_selesai' => $this->record->tanggal_selesai,
                            'status_karyawan' => $detail->employee->employment_status,
                            'nama_karyawan' => $detail->employee->nama,
                            'no_id' => $detail->employee->no_id,
                            'total_hadir' => $detail->total_hadir,
                            'gaji_kehadiran' => $detail->total_gaji_kehadiran,
                            'jam_lembur_biasa' => $detail->jam_lembur_biasa,
                            'gaji_lembur_biasa' => $detail->total_gaji_lembur_biasa,
                            'jam_lembur_libur' => $detail->jam_lembur_libur,
                            'gaji_lembur_libur' => $detail->total_gaji_lembur_libur,
                            'total_gaji' => $detail->total_gaji,
                        ]);
                    }

                    $writer->close();

                    return response()->download($tempPath, $filename, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])->deleteFileAfterSend(true);
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function () {
                    $details = $this->getFilteredDetails();

                    if ($details->isEmpty()) {
                        Notification::make()->title('Tidak ada data untuk diexport')->warning()->send();
                        return null;
                    }

                    $pdf = Pdf::loadView('filament.resources.payrolls.pages.report-payroll-pdf', [
                        'payroll' => $this->record,
                        'details' => $details,
                        'statusFilter' => $this->statusFilter,
                    ]);

                    $filename = 'payroll_' . $this->record->id . '_' . now()->format('Ymd_His') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename,
                        ['Content-Type' => 'application/pdf']
                    );
                }),
            \Filament\Actions\Action::make('rincian')
                ->label('Rincian Perhitungan')
                ->icon('heroicon-o-calculator')
                ->color('info')
                ->modalHeading(fn($record) => $record ? 'Formula Gaji: ' . $record->employee->nama : 'Memuat...')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->form(fn($record) => [
                    \Filament\Forms\Components\Placeholder::make('rumus_body')
                        ->hiddenLabel()
                        ->content(fn($record) => view('filament.resources.payrolls.pages.rumus-modals', ['record' => $record]))
                ])
                ->record(function (array $arguments) {
                    return \App\Models\PayrollDetail::find($arguments['record'] ?? null);
                }),

            \Filament\Actions\Action::make('kirim_slip')
                ->label('Kirim Slip (Email)')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->requiresConfirmation()
                ->record(function (array $arguments) {
                    return \App\Models\PayrollDetail::find($arguments['record'] ?? null);
                })
                ->modalHeading(fn($record) => $record ? "Kirim Slip Gaji: {$record->employee->nama}" : "Kirim Slip Gaji")
                ->modalDescription(fn($record) => $record ? new \Illuminate\Support\HtmlString("Apakah Anda yakin ingin mengirim slip gaji ke email <strong>{$record->employee->nama}</strong>?" . ($record->employee->email ? " (Email: <em>{$record->employee->email}</em>)" : "")) : "")
                ->action(function ($record) {
                    if (empty($record->employee->email)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Kirim')
                            ->body('Karyawan ini belum memiliki data email.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        \Illuminate\Support\Facades\Mail::to($record->employee->email)
                            ->send(new \App\Mail\PayslipMail($record));

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil!')
                            ->body('Slip gaji terkirim ke ' . $record->employee->email)
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Mengirim Email')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }

    private function getFilteredDetails()
    {
        return $this->record->details()
            ->with('employee')
            ->when($this->statusFilter, function ($q) {
                return $q->whereHas('employee', function ($query) {
                    $query->where('employment_status', $this->statusFilter);
                });
            })
            ->orderByDesc('total_gaji')
            ->get();
    }
}
