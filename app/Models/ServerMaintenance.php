<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerMaintenance extends Model
{
    protected $table = 'server_maintenance';
    protected $fillable = [
        'server_id', 'waktu_pemeliharaan', 'temuan', 'dicek_oleh',
        'kondisi', 'status', 'gambar', 'created_by',
    ];

    public function server()
    {
        return $this->belongsTo(DataServer::class, 'server_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
