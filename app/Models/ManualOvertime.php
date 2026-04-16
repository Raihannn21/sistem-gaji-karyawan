<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ManualOvertime extends Model
{
    public const JENIS_BIASA = 'biasa';
    public const JENIS_LIBUR = 'libur';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jam_lembur' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (ManualOvertime $manualOvertime) {
            if (self::isLockedByFinalizedPayroll($manualOvertime->tanggal)) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Periode payroll untuk tanggal ini sudah final, data lembur manual tidak dapat diubah.',
                ]);
            }
        });

        static::deleting(function (ManualOvertime $manualOvertime) {
            if (self::isLockedByFinalizedPayroll($manualOvertime->tanggal)) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Periode payroll untuk tanggal ini sudah final, data lembur manual tidak dapat dihapus.',
                ]);
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
