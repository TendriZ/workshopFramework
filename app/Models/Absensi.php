<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'kartu_nfc_id',
        'waktu_scan',
        'status',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'waktu_scan' => 'datetime'
    ];

    public function kartuNfc()
    {
        return $this->belongsTo(KartuNfc::class);
    }

    public function peserta()
    {
        return $this->hasOneThrough(Peserta::class, KartuNfc::class);
    }
}