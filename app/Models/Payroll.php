<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_FINAL = 'final';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'finalized_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function finalizer()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function isFinalized(): bool
    {
        return $this->status_payroll === self::STATUS_FINAL;
    }
}
