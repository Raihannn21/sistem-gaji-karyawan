<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'approved_overtime_hours' => 'float',
        'total_jam_kerja' => 'float',
        'is_holiday' => 'boolean',
        'tanggal' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (Attendance $attendance) {
            if (!empty($attendance->tanggal)) {
                $date = Carbon::parse($attendance->tanggal);

                $attendance->is_holiday = $date->isSunday()
                    || Holiday::query()->whereDate('tanggal', $date->toDateString())->exists();
            }

            $attendance->total_jam_kerja = self::calculateTotalWorkHours(
                $attendance->scan_masuk,
                $attendance->scan_pulang,
                (bool) $attendance->is_holiday
            );

            $shouldAutoFillApprovedOvertime =
                !$attendance->isDirty('approved_overtime_hours')
                && (
                    !$attendance->exists
                    || (float) $attendance->getOriginal('approved_overtime_hours') <= 0
                );

            if ($shouldAutoFillApprovedOvertime) {
                $attendance->approved_overtime_hours = self::calculateSuggestedOvertimeHours(
                    (float) $attendance->total_jam_kerja,
                    (bool) $attendance->is_holiday
                );
            }
        });
    }

    private static function calculateTotalWorkHours($scanMasuk, $scanPulang, bool $isHoliday): float
    {
        if (empty($scanMasuk) && empty($scanPulang)) {
            return 0;
        }

        // Hari kerja biasa dengan scan parsial dianggap hadir normal sesuai jam kerja normal.
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

    private static function calculateSuggestedOvertimeHours(float $totalJamKerja, bool $isHoliday): float
    {
        if ($totalJamKerja <= 0) {
            return 0;
        }

        if ($isHoliday) {
            // Lembur hanya dihitung per jam penuh (>= 60 menit).
            return (float) floor($totalJamKerja);
        }

        $jamKerjaNormal = (float) (Setting::query()->where('key', 'jam_kerja_normal')->value('value') ?? 8);

        // Hari kerja biasa: hanya jam lembur penuh yang dihitung.
        return (float) floor(max($totalJamKerja - $jamKerjaNormal, 0));
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
