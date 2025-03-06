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
        Schema::table('products', function (Blueprint $table) {
            // Add deleted_at column for soft deletes
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Add missing columns
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique()->after('quantity');
            }
            
            if (!Schema::hasColumn('products', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('image');
            }
            
            // Rename quantity to stock if needed
            if (Schema::hasColumn('products', 'quantity') && !Schema::hasColumn('products', 'stock')) {
                $table->renameColumn('quantity', 'stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the changes
            if (Schema::hasColumn('products', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            if (Schema::hasColumn('products', 'sku')) {
                $table->dropColumn('sku');
            }
            
            if (Schema::hasColumn('products', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }
            
            if (Schema::hasColumn('products', 'stock')) {
                $table->renameColumn('stock', 'quantity');
            }
        });
    }
}; 