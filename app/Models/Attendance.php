<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Attendance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'total_jam_kerja' => 'float',
        'is_holiday' => 'boolean',
        'tanggal' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (Attendance $attendance) {
            if (self::isLockedByFinalizedPayroll($attendance->tanggal)) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Periode payroll untuk tanggal ini sudah final, data kehadiran tidak dapat diubah.',
                ]);
            }

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
        });

        static::deleting(function (Attendance $attendance) {
            if (self::isLockedByFinalizedPayroll($attendance->tanggal)) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Periode payroll untuk tanggal ini sudah final, data kehadiran tidak dapat dihapus.',
                ]);
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

        $jamKerjaNormal = (float) (Setting::query()->where('key', 'jam_kerja_normal')->value('value') ?? 8);

        return round(min($awal->diffInMinutes($akhir) / 60, $jamKerjaNormal), 2);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    private static function isLockedByFinalizedPayroll($tanggal): bool
    {
        if (empty($tanggal)) {
            return false;
        }

        $date = Carbon::parse($tanggal)->toDateString();

        return Payroll::query()
            ->where('status_payroll', Payroll::STATUS_FINAL)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_selesai', '>=', $date)
            ->exists();
    }
}
