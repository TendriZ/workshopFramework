<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'kota';
    protected $primaryKey = 'id_kota';
    protected $keyType = 'int';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = ['id_kota', 'id_provinsi', 'nama'];

    // Relationship: Kota belongs to Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    // Relationship: 1 Kota has many Kecamatan (plural form)
    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'id_kota', 'id_kota');
    }
}