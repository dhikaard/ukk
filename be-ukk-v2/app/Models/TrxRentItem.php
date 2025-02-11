<?php

namespace App\Models;

use App\Enums\RentalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrxRentItem extends Model
{
    protected $table = 'trx_rent_items';
    protected $primaryKey = 'trx_rent_items_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function globalFine(): BelongsTo
    {
        return $this->belongsTo(GlobalFine::class, 'global_fine_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrxRentItemDetail::class, 'trx_rent_items_id');
    }

    protected $casts = [
        'status' => RentalStatus::class,
    ];

    protected $fillable = [
        'trx_code',
        'user_id',
        'rent_start_date',
        'rent_end_date',
        'duration',
        'return_date',
        'status',
        'fine_amount',
        'desc',
        'total',
        'total_fine_amount',
    ];

    // public function calculateFineAmount()
    // {
    //     $now = now();
    //     $rentEndDate = Carbon::parse($this->rent_end_date)->endOfDay();
    //     $fineAmount = 0;

    //     foreach ($this->details as $detail) {
    //         $item = $detail->item;
    //         if ($item && $item->globalFine && $now->greaterThan($rentEndDate)) {
    //             $timeLimit = Carbon::parse($rentEndDate)
    //                 ->setTimeFrom(Carbon::parse($item->globalFine->time_limit));
                
    //             if ($now->greaterThan($timeLimit)) {
    //                 $fineAmount += $item->price * $detail->qty;
    //             } else {
    //                 $fineAmount += ($item->globalFine->fine_percentage / 100) * $item->price * $detail->qty;
    //             }
    //         }
    //     }

    //     $this->total_fine_amount = $fineAmount;
    //     $this->save();
    // }

    // }

    // public function calculateFineAmountForDetails(array $details, string $rentEndDate, int $globalFineId): array
    // {
    //     $now = now();
    //     $rentEndDate = Carbon::parse($rentEndDate)->endOfDay();
    //     $globalFine = GlobalFine::find($globalFineId);

    //     if ($globalFine) {
    //         $timeLimit = Carbon::parse($rentEndDate)->setTimeFrom(Carbon::parse($globalFine->time_limit));
    //         $finePercentage = $globalFine->fine_percentage;

    //         foreach ($details as &$detail) {
    //             $item = Items::find($detail['items_id']);
    //             if ($item) {
    //                 if ($now->greaterThan($rentEndDate)) {
    //                     if ($now->greaterThan($timeLimit)) {
    //                         $detail['fine_amount'] = $item->price * $detail['qty'];
    //                     } else {
    //                         $detail['fine_amount'] = ($finePercentage / 100) * $item->price * $detail['qty'];
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $details;
    // }

}