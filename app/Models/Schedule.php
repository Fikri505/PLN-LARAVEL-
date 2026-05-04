<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'transaction_id', 'start_date', 'end_date', 'pic_acara', 'nama_acara',
        'pic_it_support', 'meeting_room', 'pelaksanaan', 'standby_status',
        'kebutuhan_detail', 'tindak_lanjut',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
