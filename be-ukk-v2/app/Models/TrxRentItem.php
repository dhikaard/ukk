<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TrxRentItem extends Model
{
    protected $table = 'trx_rent_items';
    protected $primaryKey = 'trx_rent_items_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class, 'items_id');
    }

    public function globalFine(): BelongsTo
    {
        return $this->belongsTo(GlobalFine::class, 'global_fine_id');
    }

    protected $fillable = [
        'user_id',
        'items_id',
        'rent_start_date',
        'rent_end_date',
        'duration',
        'return_date',
        'status',
        'qty',
        'global_fine_id',
        'fine_amount',
        'sub_total',
        'desc',
    ];

    public function calculateFineAmount()
    {
        $now = now();
        $rentEndDate = Carbon::parse($this->rent_end_date)->endOfDay();
        $globalFine = $this->globalFine;
    
        if ($globalFine) {
            $timeLimit = Carbon::parse($this->rent_end_date)->setTimeFrom(Carbon::parse($globalFine->time_limit));
            $finePercentage = $globalFine->fine_percentage;
            $fineAmount = 0;
    
            // Jika waktu sekarang lebih besar dari rent_end_date
            if ($now->greaterThan($rentEndDate)) {
                // Jika melewati time_limit, denda menjadi 100%
                if ($now->greaterThan($timeLimit)) {
                    $fineAmount = $this->item->price * $this->qty;
                } else {
                    // Jika masih dalam batas time_limit, gunakan fine_percentage
                    $fineAmount = ($finePercentage / 100) * $this->item->price * $this->qty;
                }
            }
    
            $this->fine_amount = min($fineAmount, $this->item->price * $this->qty);
        } else {
            $this->fine_amount = 0;
        }
    }
    

    public function calculateDuration()
    {
        $startDate = Carbon::parse($this->rent_start_date)->startOfDay();
        $endDate = Carbon::parse($this->rent_end_date)->startOfDay();

        if ($endDate->lessThan($startDate)) {
            throw new \Exception('End date cannot be earlier than start date');
        } else {
            $this->duration = $startDate->diffInDays($endDate) + 1; // Tambahkan 1 hari untuk menyertakan hari mulai
        }
    }

    public function calculateSubTotal()
    {
        $item = $this->item;
        if ($item) {
            $this->sub_total = $item->price * $this->qty * $this->duration;
        } else {
            $this->sub_total = 0;
        }
    }

    protected static function booted()
    {
        static::saving(function ($trxRentItem) {
            $trxRentItem->calculateDuration();
            $trxRentItem->calculateSubTotal();
            $trxRentItem->calculateFineAmount();
        });

        static::retrieved(function ($trxRentItem) {
            $trxRentItem->calculateDuration();
            $trxRentItem->calculateSubTotal();
            $trxRentItem->calculateFineAmount();
        });
    }
}