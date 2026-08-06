<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomVerifyEmailNotification;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'avatar',
        'password',
        'employee_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->contains('is_super_admin', true);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) return true;

        return $this->roles
            ->flatMap(fn($role) => $role->permissions)
            ->contains('name', $permission);
    }

    public function can($abilities, $arguments = []): bool
    {
        if (is_string($abilities)) {
            return $this->hasPermission($abilities);
        }
        return parent::can($abilities, $arguments);
    }
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'inactive' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
            default    => 'bg-gray-100 text-gray-600',
        };
    }
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) return null;

        $path = ltrim(str_replace('public/', '', $this->avatar), '/');
        return Storage::disk('public')->url($path);
    }
    // User.php
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin' || $this->is_admin == 1;
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification());
    }
}
