<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';
    protected $primaryKey = 'id_kelurahan';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    
    protected $fillable = ['id_kelurahan', 'id_kecamatan', 'nama'];

    // Relationship: Kelurahan belongs to Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}