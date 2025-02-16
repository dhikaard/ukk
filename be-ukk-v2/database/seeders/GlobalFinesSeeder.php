<?php

namespace Database\Seeders;

use App\Models\GlobalFine;
use Illuminate\Database\Seeder;

class GlobalFinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalFine::create([
            'fine_name' => 'Denda Standar',
            'fine_percentage' => 50,
            'time_limit' => '12:00:00'
        ]);
    }
}