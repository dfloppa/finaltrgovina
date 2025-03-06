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
            $table->foreignId('preferred_payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->foreignId('preferred_shipping_method_id')->nullable()->constrained('shipping_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['preferred_payment_method_id']);
            $table->dropForeign(['preferred_shipping_method_id']);
            $table->dropColumn(['preferred_payment_method_id', 'preferred_shipping_method_id']);
        });
    }
};
