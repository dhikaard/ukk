<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Items extends Model
{

    protected $primaryKey = 'items_id';
    public $incrementing = true;

    public function ctgr_items(): BelongsTo
    {
        return $this->belongsTo(CategoryItems::class, 'ctgr_items_id');
    }
    
    public function itemStock(): HasMany
    {
        return $this->hasMany(ItemsStock::class, 'items_id');
    }


    protected $fillable = [
        'items_name',
        'items_code',
        'ctgr_items_id',
        'desc',
        'stock',
        'price',
        'global_fine_id',
        'image',
        'active',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($items) {
            $prefix = strtoupper(substr($items->items_name, 0, 3));
            $date = now()->format('dmy');

            $latestItem = static::latest('items_id')->first();
            $id = $latestItem ? $latestItem->items_id + 1 : 1;

            $items->items_code = "{$prefix}-{$date}-{$id}";
        });
    }

    public function getTotalStockAttribute()
    {
        // Menghitung jumlah stock produk utama dan itemStock
        return $this->stock + $this->itemStock->sum('stock');
    }

}