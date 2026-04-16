<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'jam_lembur_biasa' => 'integer',
        'jam_lembur_libur' => 'integer',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
