<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use App\Models\Items;
use App\Models\TrxRentItem;
use App\Models\TrxRentItemDetail;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTrxRentItem extends CreateRecord
{
    protected static string $resource = TrxRentItemResource::class;

    protected function handleRecordCreation(array $data): Model 
    {
        // Log complete data structure first
        Log::info('Raw form data:', [
            'data' => $data,
            'details' => $data['details'] ?? 'No details found'
        ]);

        return DB::transaction(function () use ($data) {
            // Calculate duration
            $startDate = Carbon::parse($data['rent_start_date']);
            $endDate = Carbon::parse($data['rent_end_date']);
            $duration = $startDate->diffInDays($endDate) + 1;

            // Get and log details
            $details = $data['details'] ?? [];
            Log::info('Processing details:', ['details' => $details]);

            $total = 0;
            
            // Calculate and log total
            foreach ($details as $detail) {
                $item = Items::find($detail['items_id']);
                if ($item) {
                    $subTotal = $item->price * $detail['qty'] * $duration;
                    $total += $subTotal;
                    
                    Log::info('Calculated subtotal:', [
                        'item_id' => $detail['items_id'],
                        'price' => $item->price,
                        'qty' => $detail['qty'],
                        'duration' => $duration,
                        'subtotal' => $subTotal
                    ]);
                }
            }

            Log::info('Final calculation:', [
                'total' => $total,
                'duration' => $duration
            ]);

            // Create main transaction
            $trxRentItem = TrxRentItem::create([
                'trx_code' => $data['trx_code'],
                'user_id' => $data['user_id'],
                'rent_start_date' => $data['rent_start_date'],
                'rent_end_date' => $data['rent_end_date'],
                'duration' => $duration,
                'status' => 'P',
                'total' => $data['total'],
                'total_fine_amount' => 0,
                'desc' => $data['desc'] ?? ''
            ]);

            // Create details with logging
            foreach ($details as $detail) {
                $item = Items::find($detail['items_id']);
                $subTotal = $item->price * $detail['qty'] * $duration;
                
                TrxRentItemDetail::create([
                    'trx_rent_items_id' => $trxRentItem->trx_rent_items_id,
                    'items_id' => $detail['items_id'],
                    'item_stock_id' => $detail['item_stock_id'] ?? -99,
                    'qty' => $detail['qty'],
                    'sub_total' => $subTotal,
                    'fine_amount' => 0
                ]);
            }

            return $trxRentItem;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}