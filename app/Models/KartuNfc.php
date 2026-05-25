<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuNfc extends Model
{
    protected $fillable = [
        'serial_number',
        'nama_kartu',
        'jenis',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function peserta()
    {
        return $this->hasOne(Peserta::class, 'kartu_nfc_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}