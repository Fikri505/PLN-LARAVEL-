<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiPerangkat extends Model
{
    protected $table = 'mutasi_perangkat';
    protected $fillable = [
        'stock_perangkat_id',
        'tipe',
        'jumlah',
        'kondisi',
        'keterangan',
        'created_by',
    ];

    public function perangkat()
    {
        return $this->belongsTo(StockPerangkat::class, 'stock_perangkat_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}