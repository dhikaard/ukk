<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    protected $table = 'product_stock';
    protected $primaryKey = 'product_stock_id';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'products_id');
    }

    protected $fillable = [
        'products_id',
        'size',
        'stock',
    ];
}
