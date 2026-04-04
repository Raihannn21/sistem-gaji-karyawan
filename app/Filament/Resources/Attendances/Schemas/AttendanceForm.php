<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(fn () => Employee::query()->orderBy('nama')->pluck('nama', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                DatePicker::make('tanggal')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $isHoliday = self::resolveHolidayStatus($state);
                        $totalJam = self::calculateTotalWorkHours($get('scan_masuk'), $get('scan_pulang'), $isHoliday);

                        $set('is_holiday', $isHoliday);
                        $set('total_jam_kerja', $totalJam);
                        $set('approved_overtime_hours', self::calculateSuggestedOvertimeHours($totalJam, $isHoliday));
                    }),
                TimePicker::make('scan_masuk')
                    ->seconds(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $isHoliday = self::resolveHolidayStatus($get('tanggal'));
                        $totalJam = self::calculateTotalWorkHours($state, $get('scan_pulang'), $isHoliday);

                        $set('total_jam_kerja', $totalJam);
                        $set('is_holiday', $isHoliday);
                        $set('approved_overtime_hours', self::calculateSuggestedOvertimeHours($totalJam, $isHoliday));
                    }),
                TimePicker::make('scan_pulang')
                    ->seconds(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $isHoliday = self::resolveHolidayStatus($get('tanggal'));
                        $totalJam = self::calculateTotalWorkHours($get('scan_masuk'), $state, $isHoliday);

                        $set('total_jam_kerja', $totalJam);
                        $set('is_holiday', $isHoliday);
                        $set('approved_overtime_hours', self::calculateSuggestedOvertimeHours($totalJam, $isHoliday));
                    }),
                TextInput::make('total_jam_kerja')
                    ->label('Total Jam Kerja (Otomatis)')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->default(0),
                TextInput::make('approved_overtime_hours')
                    ->label('Lembur Disetujui Atasan (Jam)')
                    ->helperText('Terisi otomatis dari durasi kerja, tetap bisa Anda koreksi jika ada instruksi atasan berbeda.')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_holiday')
                    ->label('Hari Libur (Otomatis)')
                    ->helperText('Ditentukan dari hari Minggu atau tanggal merah pada kalender libur.')
                    ->disabled()
                    ->required(),
            ]);
    }

    private static function calculateTotalWorkHours($scanMasuk, $scanPulang, bool $isHoliday): float
    {
        if (empty($scanMasuk) && empty($scanPulang)) {
            return 0;
        }

        if (empty($scanMasuk) || empty($scanPulang)) {
            if ($isHoliday) {
                return 0;
            }

            return (float) (Setting::query()->where('key', 'jam_kerja_normal')->value('value') ?? 8);
        }

        $awal = Carbon::parse($scanMasuk);
        $akhir = Carbon::parse($scanPulang);

        if ($akhir->lessThan($awal)) {
            $akhir->addDay();
        }

        return round($awal->diffInMinutes($akhir) / 60, 2);
    }

    private static function resolveHolidayStatus($tanggal): bool
    {
        if (empty($tanggal)) {
            return false;
        }

        $date = Carbon::parse($tanggal);

        return $date->isSunday() || Holiday::query()->whereDate('tanggal', $date->toDateString())->exists();
    }

    private static function calculateSuggestedOvertimeHours(float $totalJamKerja, bool $isHoliday): float
    {
        if ($totalJamKerja <= 0) {
            return 0;
        }

        if ($isHoliday) {
            return (float) floor($totalJamKerja);
        }

        $jamKerjaNormal = (float) (Setting::query()->where('key', 'jam_kerja_normal')->value('value') ?? 8);

        return (float) floor(max($totalJamKerja - $jamKerjaNormal, 0));
    }
}
