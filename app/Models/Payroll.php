<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
