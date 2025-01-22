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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('role_id')->default(-99);
            $table->string('address', 256)->default('');
            $table->string('phone', 20)->default('');
            $table->boolean('active')->default(true);
            $table->dropColumn('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['roles_id', 'address', 'phone', 'active']);
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
