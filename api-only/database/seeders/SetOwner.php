<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetOwner extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('users')->insert([
            'user_code' => Str::uuid()->toString(),
            'role_id' => 1,
            'address' => 'Jl.pangkubowono 1',
            'phone' => '1234567890',
            'active' => 'Y',
            'name' => 'Pemilik',
            'email' => 'owner@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('owner123'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
