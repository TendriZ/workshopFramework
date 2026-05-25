<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'kartu_nfc_id',
        'kelas'
    ];

    public function kartuNfc()
    {
        return $this->belongsTo(KartuNfc::class, 'kartu_nfc_id');
    }

    public function absensis()
    {
        return $this->hasManyThrough(Absensi::class, KartuNfc::class);
    }
}