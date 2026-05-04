<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItSupportJateng extends Model
{
    public $timestamps = false;
    protected $table = 'it_support_jateng';
    protected $fillable = ['nama', 'email', 'no_hp', 'penempatan', 'ops_sti'];
}
