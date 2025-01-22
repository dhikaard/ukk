<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProducts extends Model
{
    use HasFactory;

    protected $table = 'ctgr_products';
    protected $primaryKey = 'ctgr_products_id';
    public $incrementing = true;

    protected $fillable = [
        'ctgr_products_name',
        'active',
    ];
}
