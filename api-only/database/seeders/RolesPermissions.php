<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('roles')->insert([
            [
                'role_name' => 'Owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Pengguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'role_id' => 1,
                'permission_name' => 'manageAdmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'permission_name' => 'manageProduct',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'permission_name' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
