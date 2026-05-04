<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatAplikasi extends Model
{
    public $timestamps = false;
    protected $table = 'perangkat_aplikasi';
    protected $fillable = [
        'jenis_perangkat', 'url', 'ip', 'brand', 'type', 'server', 'os',
        'lokasi', 'bidang', 'msb_sub_bidang',
        'firmware_patch', 'database_patch', 'network_device_patch',
        'application_patch', 'os_patch', 'library_dependency_patch',
        'pemilik_aset', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
