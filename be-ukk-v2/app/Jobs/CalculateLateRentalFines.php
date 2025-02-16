<?php

namespace App\Jobs;

use App\Models\TrxRentItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateLateRentalFines implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $activeRentals = TrxRentItem::where('status', 'D')
            ->whereDate('rent_end_date', '<', now())
            ->with(['details.item.globalFine'])
            ->get();

        foreach ($activeRentals as $rental) {
            DB::beginTransaction();
            try {
                $totalFineAmount = 0;
                $rentEndDate = Carbon::parse($rental->rent_end_date)->startOfDay();
                $now = Carbon::now()->startOfDay();
                
                // Calculate days late using only dates
                $daysLate = $rentEndDate->diffInDays($now);

                foreach ($rental->details as $detail) {
                    $item = $detail->item;
                    $globalFine = $item->globalFine;

                    if (!$globalFine) continue;

                    $timeLimit = Carbon::parse($rental->rent_end_date)
                        ->setTimeFromTimeString($globalFine->time_limit)
                        ->startOfDay();
                    
                    $fineAmount = 0;
                    
                    if ($daysLate > 0) {
                        // If late by 1 day and within time limit
                        if ($daysLate === 1 && $now->lte($timeLimit)) {
                            $fineAmount = ($globalFine->fine_percentage / 100) * $item->price * $detail->qty;
                        } 
                        // If late more than 1 day or past time limit
                        else {
                            $fineAmount = $item->price * $detail->qty * $daysLate;
                        }
                    }

                    $detail->update(['fine_amount' => $fineAmount]);
                    $totalFineAmount += $fineAmount;
                }

                $rental->update(['total_fine_amount' => $totalFineAmount]);
                DB::commit();

                Log::info('Fine calculated', [
                    'rental_id' => $rental->trx_rent_items_id,
                    'total_fine' => $totalFineAmount,
                    'days_late' => $daysLate
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Fine calculation failed', [
                    'rental_id' => $rental->trx_rent_items_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}