<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;
    protected string $view = 'filament.resources.payrolls.pages.view-payroll';

    public ?string $tableSearch = null;

    protected function getActions(): array
    {
        return [
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
}
