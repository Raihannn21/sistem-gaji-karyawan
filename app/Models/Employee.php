<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    public const STATUS_PHL = 'PHL';
    public const STATUS_PKWT = 'PKWT';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function manualOvertimes()
    {
        return $this->hasMany(ManualOvertime::class);
    }
}
