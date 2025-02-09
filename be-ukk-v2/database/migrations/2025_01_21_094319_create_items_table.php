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
        Schema::create('items', function (Blueprint $table) {
            $table->id('items_id');
            $table->string('items_name', 256);
            $table->string('items_code', 15);
            $table->integer('ctgr_items_id')->default(-99);
            $table->text('desc');
            $table->bigInteger('stock');
            $table->double('price');
            $table->bigInteger('global_fine_id');
            $table->boolean('active')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
