<?php

namespace App\Models;

use App\Enums\RentalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'penalty_fines',
        'payment_id',
        'payment_status',
        'payment_url',
        'flg_payment',
        'payment_method'
    ];
}