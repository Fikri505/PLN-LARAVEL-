<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoomBooking extends Model
{
    protected $fillable = [
        'booking_date', 'booking_time', 'zoom_link', 'keterangan',
        'unit', 'start_datetime', 'end_datetime', 'kondisi', 'created_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Auto-release bookings that passed their end_datetime
     */
    public static function autoRelease(): void
    {
        static::where('kondisi', 'DIPAKAI')
            ->whereNotNull('end_datetime')
            ->where('end_datetime', '<', now())
            ->update(['kondisi' => 'KOSONG']);
    }
}
