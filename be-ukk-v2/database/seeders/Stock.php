<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Stock extends Seeder
{

  public function run(): void {
    DB::table('produc')->insert([
        [
          'name' => 'Admin',
          'email' => 'admin@admin.com',
          'password' => Hash::make('admin123'),
          'role_id' => 1,
          'address' => '123 Main St',
          'phone' => '081234567890',
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          'name' => 'User 1',
          'email' => 'user1@user.com',
          'password' => Hash::make('user123'),
          'role_id' => 2,
          'address' => '123 Main St',
          'phone' => '081234567890',
          'created_at' => now(),
          'updated_at' => now(),
        ]
    ]);
  }
}
