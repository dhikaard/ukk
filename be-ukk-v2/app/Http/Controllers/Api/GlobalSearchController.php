<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\TrxRentItem;
use App\Enums\RentalStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->query('query');
            
            // Search Items
            $items = Items::where('active', true)
                ->where(function ($q) use ($query) {
                    $q->where('items_name', 'like', "%{$query}%")
                      ->orWhere('items_code', 'like', "%{$query}%");
                })
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'title' => $item->items_name,
                        'details' => [
                            'Kode' => $item->items_code,
                            'Kategori' => $item->category->ctgr_items_name,
                            'Harga' => 'Rp ' . number_format($item->price, 0, ',', '.')
                        ]
                    ];
                });

            // Search Transactions
            $transactions = TrxRentItem::where('trx_code', 'like', "%{$query}%")
                ->take(5)
                ->get()
                ->map(function ($trx) {
                    return [
                        'title' => $trx->trx_code,
                        'details' => [
                            'Status' => $trx->status,
                            'Total' => 'Rp ' . number_format($trx->total, 0, ',', '.')
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'transactions' => $transactions
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}