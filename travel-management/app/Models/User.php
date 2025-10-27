<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'theme',
        'is_admin',       // added for admin flag
        'is_superadmin',  // added for super admin flag
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',       // cast to boolean
        'is_superadmin' => 'boolean',  // cast to boolean
    ];

    /**
     * Relationship: A user has many incidents
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Accessor: Get full avatar URL or fallback to default
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    public function isSuperAdmin(): bool
{
    return $this->role === 'superadmin';
}

public function isUser(): bool
{
    return $this->role === 'user';
}

public function isAdmin(): bool
{
    return in_array($this->role, ['admin', 'superadmin']);
}
public function isPoso(): bool
{
    return $this->role === 'poso';
}

}
