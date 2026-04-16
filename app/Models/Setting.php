<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const KEY_GAJI_HARIAN_PHL = 'gaji_harian_phl';
    public const KEY_GAJI_HARIAN_PKWT = 'gaji_harian_pkwt';
    public const KEY_RATE_LEMBUR_BIASA_PHL = 'rate_lembur_biasa_phl';
    public const KEY_RATE_LEMBUR_BIASA_PKWT = 'rate_lembur_biasa_pkwt';
    public const KEY_RATE_LEMBUR_LIBUR_PHL = 'rate_lembur_libur_phl';
    public const KEY_RATE_LEMBUR_LIBUR_PKWT = 'rate_lembur_libur_pkwt';
    public const KEY_JAM_KERJA_NORMAL = 'jam_kerja_normal';

    protected $guarded = ['id'];
}
