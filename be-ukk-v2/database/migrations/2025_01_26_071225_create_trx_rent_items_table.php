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
            $table->string('trx_code');
            $table->bigInteger('user_id')->unsigned();
            $table->dateTime('rent_start_date');
            $table->dateTime('rent_end_date');
            $table->integer('duration');
            $table->dateTime('return_date')->nullable();
            $table->string('status');
            $table->double('total_fine_amount')->default(0);
            $table->double('total')->default(0);
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
