<?php

namespace Database\Seeders;

use App\Models\Items;
use App\Models\ItemsStock;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'items_name' => 'Tenda Kap 4 - Premium',
                'ctgr_items_id' => 1, // Kategori "Tenda & Flysheets"
                'desc' => 'Tenda kapasitas 4 orang dengan material premium dan waterproof',
                'stock' => 3, // Regular stock
                'price' => 35000,
                'global_fine_id' => 1,
                'image' => 'items/tenda-4.jpg',
                'active' => true,
                'sizes' => [
                    ['size' => '240 x 210', 'stock' => 2],
                    ['size' => '260 x 230', 'stock' => 2],
                ]
            ],
            [
                'items_name' => 'Sleeping Bag',
                'ctgr_items_id' => 5, // Kategori "Perlengkapan Tidur" 
                'desc' => 'Sleeping bag bahan dakron tebal dengan kualitas premium',
                'stock' => 5,
                'price' => 15000,
                'global_fine_id' => 1,
                'image' => 'items/sleeping-bag.jpg',
                'active' => true,
                'sizes' => [] // Tidak memiliki ukuran spesifik
            ],
            [
                'items_name' => 'Carrier 60L',
                'ctgr_items_id' => 4, // Kategori "Tas Gunung"
                'desc' => 'Tas carrier kapasitas 60 liter dengan banyak kompartemen',
                'stock' => 0, // Stock hanya di itemStock
                'price' => 25000,
                'global_fine_id' => 1,
                'image' => 'items/carrier-60.jpg',
                'active' => true,
                'sizes' => [
                    ['size' => 'S', 'stock' => 2],
                    ['size' => 'M', 'stock' => 3],
                    ['size' => 'L', 'stock' => 2],
                ]
            ]
        ];

        foreach ($items as $item) {
            $sizes = $item['sizes'];
            unset($item['sizes']);
            
            // Create item
            $newItem = Items::create($item);

            // Create item stocks if any
            if (!empty($sizes)) {
                foreach ($sizes as $size) {
                    ItemsStock::create([
                        'items_id' => $newItem->items_id,
                        'size' => $size['size'],
                        'stock' => $size['stock']
                    ]);
                }
            }
        }
    }
}