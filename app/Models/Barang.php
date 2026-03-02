<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama', 'harga'];

    const CREATED_AT = 'timestamp';
    const UPDATED_AT = 'timestamp';
}
