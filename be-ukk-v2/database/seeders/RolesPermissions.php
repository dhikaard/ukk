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
        DB::table('role')->insert([
            [
                'role_name' => 'ADMIN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'USER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
