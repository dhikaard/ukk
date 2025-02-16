<?php

namespace Database\Seeders;

use App\Models\CategoryItems;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'ctgr_items_name' => 'Tenda & Flysheets',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Alat Elektronik',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Alat Masak',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Tas Gunung',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Perlengkapan Tidur',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Sepatu',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Meja & Kursi',
                'active' => true
            ],
            [
                'ctgr_items_name' => 'Lain-Lain',
                'active' => true
            ],
        ];

        foreach ($categories as $category) {
            CategoryItems::create($category);
        }
    }
}