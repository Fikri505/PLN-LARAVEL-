<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'plain_password', 'role', 'bagian', 'is_active',
    ];

    protected $hidden = ['password', 'plain_password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasPermission(string $pageSlug): bool
    {
        if ($this->isAdmin()) return true;
        return $this->permissions()->where('page_slug', $pageSlug)->exists();
    }

    public function getPermissionSlugs(): array
    {
        return $this->permissions()->pluck('page_slug')->toArray();
    }
}
