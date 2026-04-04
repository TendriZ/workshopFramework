<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;
    protected $fillable = [
        'idvendor',
        'id_customer',
        'nama',
        'total',
        'metode_bayar',
        'status_bayar',
        'order_id_midtrans',
        'snap_token',
        'midtrans_response',
    ];

    const CREATED_AT = 'timestamp';
    const UPDATED_AT = null;

    protected $casts = [
        'midtrans_response' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}
