<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'page_slug'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
