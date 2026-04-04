<?php

namespace App\Filament\Resources\Payrolls\RelationManagers;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\HtmlString;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    protected static ?string $title = 'Rincian Gaji Karyawan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Kita tidak membolehkan edit manual dari dalam relation manager ini
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('employee.nama')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_hadir')
                    ->label('Total Hadir (Hari)')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_gaji_kehadiran')
                    ->label('Total Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('jam_lembur_biasa')
                    ->label('Jam LB')
                    ->tooltip('Jam Lembur Hari Biasa')
                    ->numeric(),

                TextColumn::make('total_gaji_lembur_biasa')
                    ->label('Rp Lembur Biasa')
                    ->money('IDR'),

                TextColumn::make('jam_lembur_libur')
                    ->label('Jam LL')
                    ->tooltip('Jam Lembur Hari Libur')
                    ->numeric(),

                TextColumn::make('total_gaji_lembur_libur')
                    ->label('Rp Lembur Libur')
                    ->money('IDR'),

                TextColumn::make('total_gaji')
                    ->label('Total Dibayarkan')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tidak ada tambah recod manual, semuanya engine payroll
            ])
            ->actions([
                Action::make('rincian')
                    ->label('Rincian Perhitungan')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->modalHeading(fn($record) => 'Formula Gaji: ' . $record->employee->nama)
                    ->modalSubmitAction(false) // Not a form
                    ->modalCancelActionLabel('Tutup')
                    ->schema([
                        Section::make('Rincian Hari Lembur & Kehadiran')
                            ->schema([
                                Placeholder::make('detail_harian')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->content(function ($record) {
                                        $attendances = \App\Models\Attendance::where('employee_id', $record->employee_id)
                                            ->whereBetween('tanggal', [$record->payroll->tanggal_mulai, $record->payroll->tanggal_selesai])
                                            ->orderBy('tanggal')
                                            ->get();

                                        if ($attendances->isEmpty())
                                            return new HtmlString('<p class="text-gray-500">Tidak ada log kehadiran pada periode ini.</p>');

                                        $html = '<div class="overflow-x-auto"><table class="w-full text-left border-collapse rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                            <thead class="bg-gray-100 dark:bg-gray-800 text-xs uppercase text-gray-700 dark:text-gray-300">
                                                <tr>
                                                    <th class="px-4 py-3 border dark:border-gray-700 font-semibold">Tanggal</th>
                                                    <th class="px-4 py-3 border text-center dark:border-gray-700 font-semibold">Hari Libur?</th>
                                                    <th class="px-4 py-3 border text-center dark:border-gray-700 font-semibold">Bekerja</th>
                                                    <th class="px-4 py-3 border text-center dark:border-gray-700 font-semibold">Lembur Diakui</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-sm divide-y divide-gray-200 dark:divide-gray-700">';

                                        foreach ($attendances as $att) {
                                            $isLibur = $att->is_holiday ? '<span class="text-danger-600 font-bold">Ya</span>' : '<span class="text-gray-500">Tidak</span>';

                                            $lembur = max((float) $att->approved_overtime_hours, 0);

                                            $lemburText = $lembur > 0 ? "<span class=\"font-bold text-success-600\">" . rtrim(rtrim(number_format($lembur, 2, '.', ''), '0'), '.') . " Jam</span>" : "-";
                                            $jamText = floor($att->total_jam_kerja) . "j " . round(($att->total_jam_kerja - floor($att->total_jam_kerja)) * 60) . "m";

                                            $html .= "<tr class=\"hover:bg-gray-50 dark:hover:bg-white/5 transition-colors\">
                                                <td class=\"px-4 py-2 border dark:border-gray-700\">" . \Carbon\Carbon::parse($att->tanggal)->translatedFormat('d M Y') . "</td>
                                                <td class=\"px-4 py-2 border text-center dark:border-gray-700\">{$isLibur}</td>
                                                <td class=\"px-4 py-2 border text-center dark:border-gray-700\">{$jamText}</td>
                                                <td class=\"px-4 py-2 border text-center dark:border-gray-700\">{$lemburText}</td>
                                            </tr>";
                                        }

                                        $html .= '</tbody></table></div>';
                                        return new HtmlString($html);
                                    })
                            ])->collapsed(),

                        Section::make('Bukti Matematis Pendapatan')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('total_hadir')
                                            ->label('Gaji Kehadiran')
                                            ->content(fn($record) => "{$record->total_hadir} Hari × Rp " . number_format($record->total_gaji_kehadiran / max($record->total_hadir, 1), 0, ',', '.') . " = Rp " . number_format($record->total_gaji_kehadiran, 0, ',', '.')),

                                        Placeholder::make('jam_lembur_biasa')
                                            ->label('Lembur Hari Biasa')
                                            ->content(fn($record) => rtrim(rtrim(number_format((float) $record->jam_lembur_biasa, 2, '.', ''), '0'), '.') . " Jam × Rp " . number_format($record->total_gaji_lembur_biasa / max((float) $record->jam_lembur_biasa, 1), 0, ',', '.') . " = Rp " . number_format($record->total_gaji_lembur_biasa, 0, ',', '.')),

                                        Placeholder::make('jam_lembur_libur')
                                            ->label('Lembur Hari Libur')
                                            ->content(fn($record) => rtrim(rtrim(number_format((float) $record->jam_lembur_libur, 2, '.', ''), '0'), '.') . " Jam × Rp " . number_format($record->total_gaji_lembur_libur / max((float) $record->jam_lembur_libur, 1), 0, ',', '.') . " = Rp " . number_format($record->total_gaji_lembur_libur, 0, ',', '.')),

                                        Placeholder::make('total_gaji')
                                            ->label('TOTAL PENDAPATAN AKHIR')
                                            ->content(fn($record) => "Rp " . number_format($record->total_gaji_kehadiran, 0, ',', '.') . " + Rp " . number_format($record->total_gaji_lembur_biasa, 0, ',', '.') . " + Rp " . number_format($record->total_gaji_lembur_libur, 0, ',', '.') . " = Rp " . number_format($record->total_gaji, 0, ',', '.'))
                                            ->extraAttributes(['class' => 'font-bold text-success-600 text-lg']),
                                    ]),
                            ])
                            ->collapsible(),
                    ]),

                Action::make('kirim_slip')
                    ->label('Kirim Slip (Email)')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Slip Gaji via Email')
                    ->modalDescription(fn($record) => new \Illuminate\Support\HtmlString("Apakah Anda yakin ingin mengirim slip gaji ini ke email karyawan <strong>{$record->employee->nama}</strong>?" . ($record->employee->email ? " (Email: <em>{$record->employee->email}</em>)" : "<br><br><span class=\"text-danger-600 font-bold\">Perhatian:</span> Karyawan ini belum mendaftarkan email di sistem.")))
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
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('kirim_slip_semua')
                        ->label('Kirim Email (Banyak)')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Kirim Slip Gaji via Email')
                        ->modalDescription('Aksi ini akan mengirimkan email slip gaji secara serentak ke semua karyawan yang dipilih. Karyawan yang tidak memiliki alamat email akan otomatis dilewati.')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $successCount = 0;
                            $failCount = 0;

                            foreach ($records as $record) {
                                if (empty($record->employee->email)) {
                                    $failCount++;
                                    continue;
                                }

                                try {
                                    \Illuminate\Support\Facades\Mail::to($record->employee->email)
                                        ->send(new \App\Mail\PayslipMail($record));
                                    $successCount++;
                                } catch (\Exception $e) {
                                    \Illuminate\Support\Facades\Log::error('Gagal kirim email: ' . $e->getMessage());
                                    $failCount++;
                                }
                            }

                            $notification = \Filament\Notifications\Notification::make()
                                ->title('Proses Selesai');

                            if ($successCount > 0) {
                                $notification->body("Berhasil mengirim $successCount slip gaji. " . ($failCount > 0 ? "$failCount gagal/tidak ada email." : ""))
                                    ->success();
                            } else {
                                $notification->body('Semua pengiriman gagal atau email karyawan belum diisi.')
                                    ->danger();
                            }

                            $notification->send();
                        })
                ]),
            ]);
    }
}

