<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemsStock extends Model
{
    protected $table = 'item_stock';
    protected $primaryKey = 'item_stock_id';

    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class, 'items_id');
    }

    protected $fillable = [
        'items_id',
        'size',
        'stock',
    ];
}
