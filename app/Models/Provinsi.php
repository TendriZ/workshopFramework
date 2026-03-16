<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';
    protected $primaryKey = 'id_provinsi';
    protected $keyType = 'int'; // Explicitly set key type
    public $incrementing = false; // Non auto-increment ID
    public $timestamps = false;
    
    protected $fillable = ['id_provinsi', 'nama'];

    // Relationship: 1 Provinsi has many Kota (plural form)
    public function kotas()
    {
        return $this->hasMany(Kota::class, 'id_provinsi', 'id_provinsi');
    }
}