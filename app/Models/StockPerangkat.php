<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPerangkat extends Model
{
    public $timestamps = false;
    protected $table = 'stock_perangkat';
    protected $fillable = [
        'nama_barang', 'type_barang', 'jumlah', 'status',
        'kondisi', 'keterangan', 'foto', 'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mutasi()
    {
        return $this->hasMany(MutasiPerangkat::class, 'stock_perangkat_id');
    }
}