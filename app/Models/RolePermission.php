<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'menu_key', 'can_view', 'can_add'];

    protected $casts = [
        'can_view' => 'boolean',
        'can_add'  => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
