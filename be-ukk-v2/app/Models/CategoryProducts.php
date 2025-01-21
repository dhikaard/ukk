<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProducts extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_products_id'; // Sesuaikan dengan nama kolom primary key
    public $incrementing = true; // Pastikan primary key auto increment

    protected $fillable = [
        'category_products_name',
        'active',
    ];
}
