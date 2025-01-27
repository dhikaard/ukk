<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalFine extends Model
{
    use HasFactory;

    protected $table = 'global_fines';
    protected $primaryKey = 'global_fine_id';
    protected $fillable = ['fine_name', 'fine_percentage', 'time_limit'];

    // Relasi ke tabel items
    public function items()
    {
        return $this->hasMany(Items::class, 'global_fine_id');
    }

    // Relasi ke tabel trx_rent_items
    public function trxRentItems()
    {
        return $this->hasMany(TrxRentItem::class, 'global_fine_id');
    }
}
