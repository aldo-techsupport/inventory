<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = DB::table('roles')->where('role', 'superadmin')->first();

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@admin.com'],
            [
                'name'       => 'Admin',
                'email'      => 'admin@admin.com',
                'password'   => Hash::make('123456'),
                'role_id'    => $superadminRole?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
