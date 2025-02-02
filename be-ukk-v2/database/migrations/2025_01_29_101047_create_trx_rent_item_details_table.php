<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_rent_items_detail', function (Blueprint $table) {
            $table->id('trx_rent_items_detail_id');
            $table->bigInteger('trx_rent_items_id')->unsigned();
            $table->bigInteger('items_id');
            $table->bigInteger('item_stock_id')->nullable(); // Add this line
            $table->double('sub_total');
            $table->double('fine_amount');
            $table->bigInteger('qty');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_rent_items_detail');
    }
};