<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicItSupport extends Model
{
    public $timestamps = false;
    protected $table = 'pic_it_support';
    protected $fillable = ['name', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id'); }
}
