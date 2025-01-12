<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 10);
            $table->timestamps();
        });

        // permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id');
            $table->bigInteger('role_id')->default(-99);
            $table->string('permission_name', 256);
            $table->timestamps();
        });
        
        // ctgr_products
        Schema::create('ctgr_products', function (Blueprint $table) {
            $table->id('ctgr_product_id');
            $table->string('ctgr_product_name', 256);
            $table->timestamps();
        });

        // product_brand
        Schema::create('product_brand', function (Blueprint $table) {
            $table->id('product_brand_id');
            $table->string('brand_name', 256);
            $table->string('active', 1);
            $table->timestamps();
        });

        // products
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name', 256);
            $table->bigInteger('ctgr_product_id')->default(-99);
            $table->bigInteger('product_brand_id')->default(-99);
            $table->text('desc');
            $table->bigInteger('stock');
            $table->double('price');
            $table->double('fine_bill');
            $table->string('active', 1)->default('Y');
            $table->text('url_img')->nullable();
            $table->timestamps();
        });

        // product_stock
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id('product_stock_id');
            $table->bigInteger('product_id');
            $table->bigInteger('size');
            $table->bigInteger('stock');
            $table->timestamps();
        });

        // Membuat tabel trx_rent_products
        Schema::create('trx_rent_products', function (Blueprint $table) {
            $table->id('trx_rent_product_id');
            $table->bigInteger('user_id')->default(-99);
            $table->bigInteger('product_id')->default(-99);
            $table->bigInteger('duration');
            $table->string('rent_start_date', 14);
            $table->string('rent_end_date', 14);
            $table->string('status', 1);
            $table->bigInteger('qty');
            $table->string('return_date', 14)->nullable();
            $table->text('desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_rent_products');
        Schema::dropIfExists('product_stock');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_brand');
        Schema::dropIfExists('ctgr_products');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};