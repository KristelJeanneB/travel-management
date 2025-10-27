<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'role',          // Ensure role is mass assignable
        'is_admin',      // optional flag
        'is_superadmin', // optional flag
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
        'is_admin' => 'boolean',
        'is_superadmin' => 'boolean',
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
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    /**
     * Role check helpers
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isPoso(): bool
    {
        return $this->role === 'poso';
    }

    /**
     * Admin combined check (superadmin or admin)
     */
    public function isAnyAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }
}
