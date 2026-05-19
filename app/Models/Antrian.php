<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = [
        'nomor_antrian',
        'nama',
        'status',
        'loket',
        'called_at'
    ];

    protected $casts = [
        'called_at' => 'datetime'
    ];

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting')->orderBy('created_at', 'asc');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called')->orderBy('called_at', 'desc');
    }

    public function scopeSkipped($query)
    {
        return $query->where('status', 'skipped')->orderBy('created_at', 'asc');
    }

    public static function generateNomor()
    {
        $lastAntrian = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastAntrian ? (int) filter_var($lastAntrian->nomor_antrian, FILTER_SANITIZE_NUMBER_INT) : 0;
        return str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}