<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // 1 Roles bisa dimiliki banyak user
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    // 1 Role punya banyak permission
    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    /**
     * Cek apakah role ini boleh melihat menu tertentu
     */
    public function canView(string $menuKey): bool
    {
        return $this->permissions()
            ->where('menu_key', $menuKey)
            ->where('can_view', true)
            ->exists();
    }

    /**
     * Cek apakah role ini boleh menambah/edit/hapus di menu tertentu
     */
    public function canAdd(string $menuKey): bool
    {
        return $this->permissions()
            ->where('menu_key', $menuKey)
            ->where('can_add', true)
            ->exists();
    }
}
