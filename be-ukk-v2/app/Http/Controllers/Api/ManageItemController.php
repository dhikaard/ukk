<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryItems;
use App\Models\Items;
use App\Models\TrxRentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Items::with(['ctgr_items', 'itemStock'])
            ->where('active', true);

        // Apply search filter
        if ($request->has('keyword')) {
            $query->where('items_name', 'like', '%' . $request->keyword . '%');
        }

        // Apply category filter
        if ($request->has('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $query->whereIn('ctgr_items_id', $categories);
        }

        // Apply sorting
        if ($request->has('sort')) {
            $sortParams = explode(' ', $request->sort);
            if (count($sortParams) === 2) {
                $query->orderBy($sortParams[0], $sortParams[1]);
            }
        } else {
            // Default sorting
            $query->orderBy('items_name', 'ASC');
        }

        // Apply pagination
        $items = $query->paginate($request->input('limit', 10));

        // Transform items
        $items->through(function ($item) {
            $item->image = $item->image ? asset('storage/' . $item->image) : null;
            return $item;
        });

        return response()->json($items);
    }

    public function getCategories()
    {
        $categories = CategoryItems::where('active', true)
            ->select('ctgr_items_id as code', 'ctgr_items_name as name')
            ->get();

        return response()->json($categories);
    }

    public function showDetail($itemsCode)
    {
        $item = Items::with(['ctgr_items', 'itemStock'])
            ->where('items_code', $itemsCode)
            ->where('active', true)
            ->firstOrFail();

        // Transform item
        $item->image = $item->image ? asset('storage/' . $item->image) : null;

        return response()->json($item);
    }

    public function addRent(Request $request)
    {
        try {
            // JWT auth check is handled by middleware
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            // Validate request
            $request->validate([
                'rent_start_date' => 'required|date_format:Y-m-d H:i:s',
                'rent_end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:rent_start_date',
                'items' => 'required|array',
                'items.*.items_id' => 'required|exists:items,items_id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.size' => 'nullable'
            ]);

            // Calculate duration
            $startDate = \Carbon\Carbon::parse($request->rent_start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->rent_end_date)->startOfDay();
            $duration = $startDate->diffInDays($endDate) + 1;

            // Begin transaction
            DB::beginTransaction();
            try {
                // Get first item to get global_fine_id
                $firstItem = Items::findOrFail($request->items[0]['items_id']);

                // Extract prefix from items_code
                $itemPrefix = explode('-', $firstItem->items_code)[0];
                $latestTrx = TrxRentItem::latest('trx_rent_items_id')->first();
                $id = $latestTrx ? $latestTrx->trx_rent_items_id + 1 : 1;

                // Create rent transaction
                $rentItem = new TrxRentItem([
                    'trx_code' => "TRX-{$itemPrefix}-" . date('dmy') . "-{$id}",
                    'user_id' => $user->id,
                    'rent_start_date' => $request->rent_start_date,
                    'rent_end_date' => $request->rent_end_date,
                    'duration' => $duration,
                    'status' => 'P', // Pending status
                    'total' => 0,
                    'total_fine_amount' => 0
                ]);
                $rentItem->save();

                // Create rent details
                $total = 0;
                foreach ($request->items as $item) {
                    $itemData = Items::findOrFail($item['items_id']);

                    // Validate stock availability
                    $availableStock = $item['size']
                        ? $itemData->itemStock->where('size', $item['size'])->first()->stock
                        : $itemData->stock;

                    if ($availableStock < $item['qty']) {
                        throw new \Exception("Stock tidak mencukupi untuk {$itemData->items_name}");
                    }

                    // Calculate subtotal
                    $subTotal = $itemData->price * $item['qty'] * $duration;
                    
                    // Get item_stock_id if size is specified
                    $itemStockId = null;
                    if ($item['size']) {
                        $itemStock = $itemData->itemStock()
                            ->where('size', $item['size'])
                            ->first();
                        if ($itemStock) {
                            $itemStockId = $itemStock->item_stock_id;
                        }
                    }

                    // Create detail
                    $rentItem->details()->create([
                        'items_id' => $item['items_id'],
                        'item_stock_id' => $itemStockId ?? -99,
                        'qty' => $item['qty'],
                        'sub_total' => $subTotal,
                        'fine_amount' => 0
                    ]);

                    $total += $subTotal;
                }

                // Update total
                $rentItem->update(['total' => $total]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Pesanan berhasil dibuat',
                    'data' => $rentItem->load('details.item')
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

public function getRentHistory()
{
    try {
        $user = auth('api')->user();
        $rentItems = TrxRentItem::with([
            'details.item.ctgr_items',
            'details.itemStock'
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform the items to include full image URLs
        $rentItems->transform(function ($rentItem) {
            $rentItem->details->transform(function ($detail) {
                if ($detail->item && $detail->item->image) {
                    if (!str_starts_with($detail->item->image, 'http')) {
                        $detail->item->image = asset('storage/' . ltrim($detail->item->image, '/'));
                    }
                }
                return $detail;
            });
            return $rentItem;
        });

        return response()->json($rentItems);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 400);
    }
}
    public function cancelRent($id)
    {
        try {
            $user = auth('api')->user();
            $rentItem = TrxRentItem::where('user_id', $user->id)
                ->where('trx_rent_items_id', $id)
                ->where('status', 'P')
                ->firstOrFail();

            DB::beginTransaction();
            try {
                // Update status
                $rentItem->update(['status' => 'B']);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pesanan berhasil dibatalkan'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}