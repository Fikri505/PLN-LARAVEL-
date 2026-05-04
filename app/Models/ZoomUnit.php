<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoomUnit extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id'); }
}
