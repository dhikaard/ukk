<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Products extends Model
{

    protected $primaryKey = 'products_id';
    public $incrementing = true;

    public function ctgr_products(): BelongsTo
    {
        return $this->belongsTo(CategoryProducts::class, 'ctgr_products_id');
    }

    public function productStock(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'products_id');
    }


    protected $fillable = [
        'products_name',
        'products_code',
        'ctgr_products_id',
        'desc',
        'stock',
        'price',
        'fine_bill',
        'image',
        'active',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($products) {
            $prefix = strtoupper(substr($products->products_name, 0, 3));
            $random = strtoupper(Str::random(5));

            // Ambil ID terbaru berdasarkan primary key yang benar
            $latestProduct = static::latest('products_id')->first();
            $id = $latestProduct ? $latestProduct->products_id + 1 : 1;

            $products->products_code = "{$prefix}-{$random}-{$id}";
        });
        
    }

    public function getTotalStockAttribute()
    {
        // Menghitung jumlah stock produk utama dan productStock
        return $this->stock + $this->productStock->sum('stock');
    }
    
}
