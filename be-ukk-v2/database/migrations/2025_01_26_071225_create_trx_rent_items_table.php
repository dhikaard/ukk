<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trx_rent_items', function (Blueprint $table) {
            $table->id('trx_rent_items_id');
            $table->bigInteger('user_id');
            $table->bigInteger('items_id');
            $table->string('rent_start_date', 14);
            $table->string('rent_end_date', 14);
            $table->bigInteger('duration');
            $table->string('status', 1);
            $table->bigInteger('qty');
            $table->bigInteger('global_fine_id');
            $table->double('sub_total');
            $table->double('fine_amount');
            $table->string('return_date', 14)->nullable();
            $table->text('desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_rent_items');
    }
};
