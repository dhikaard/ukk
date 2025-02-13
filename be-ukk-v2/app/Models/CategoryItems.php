<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryItems extends Model
{
    use HasFactory;

    protected $table = 'ctgr_items';
    protected $primaryKey = 'ctgr_items_id';
    public $incrementing = true;
    
    protected $fillable = [
        'ctgr_items_name',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Items::class, 'ctgr_items_id', 'ctgr_items_id');
    }
}
