<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRentItemDetail extends Model
{
    use HasFactory;

    protected $table = 'trx_rent_items_detail';
    protected $primaryKey = 'trx_rent_items_detail_id';
    
    public function trxRentItem()
    {
        return $this->belongsTo(TrxRentItem::class, 'trx_rent_items_id');
    }

    public function itemStock()
    {
        return $this->belongsTo(ItemsStock::class, 'item_stock_id');
    }

    
    public function item()
    {
        return $this->belongsTo(Items::class, 'items_id');
    }
    
    protected $fillable = ['trx_rent_items_id', 'items_id', 'qty', 'item_stock_id', 'sub_total', 'fine_amount'];
}