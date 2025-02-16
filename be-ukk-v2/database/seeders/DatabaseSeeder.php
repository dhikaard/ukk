<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolesPermissions;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RolesPermissions::class,
            UserSeeder::class,
            CategorySeeder::class,
            GlobalFinesSeeder::class,
            ItemsSeeder::class
        ]);
    }
}
