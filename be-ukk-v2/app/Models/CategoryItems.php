<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItems extends Model
{
    use HasFactory;

    protected $table = 'ctgr_items';
    protected $primaryKey = 'ctgr_items_id';
    public $incrementing = true;

    protected $fillable = [
        'ctgr_items_name',
        'active',
    ];
}
