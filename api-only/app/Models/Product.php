<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'product_code',
        'ctgr_product_id',
        'product_brand_id',
        'desc',
        'stock',
        'price',
        'fine_bill',
        'url_img',
        'active',
    ];
}
