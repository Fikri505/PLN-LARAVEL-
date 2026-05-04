<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataServer extends Model
{
    protected $fillable = [
        'ind', 'fungsi_server', 'ip', 'detail', 'merk', 'type', 'system_operasi',
        'processor_merk', 'processor_type', 'processor_kecepatan', 'processor_keping', 'processor_core',
        'ram_jenis', 'ram_kapasitas', 'ram_jumlah_keping',
        'storage_jenis', 'storage_jumlah', 'storage_kapasitas_total',
        'keterangan_tambahan', 'server_fisik', 'gambar', 'status_server', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenances()
    {
        return $this->hasMany(ServerMaintenance::class, 'server_id');
    }
}
