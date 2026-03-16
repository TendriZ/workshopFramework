<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';
    protected $keyType = 'int';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = ['id_kecamatan', 'id_kota', 'nama'];

    // Relationship: Kecamatan belongs to Kota
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    // Relationship: 1 Kecamatan has many Kelurahan (plural form)
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }
}