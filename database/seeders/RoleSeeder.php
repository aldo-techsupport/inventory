<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'kepala gudang', 'admin gudang'];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['role' => $role],
                ['role' => $role, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
