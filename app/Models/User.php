<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'role_id'
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
    ];


    // 1 User hanya memiliki 1 role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Cek apakah user adalah superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role?->role === 'superadmin';
    }

    /**
     * Cek apakah user boleh melihat menu tertentu
     * Superadmin selalu bisa akses semua menu
     */
    public function canViewMenu(string $menuKey): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->role?->canView($menuKey) ?? false;
    }

    /**
     * Cek apakah user boleh tambah/edit/hapus di menu tertentu
     * Superadmin selalu bisa
     */
    public function canAddMenu(string $menuKey): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->role?->canAdd($menuKey) ?? false;
    }
}
