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
        Schema::table('payment_methods', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('payment_methods', 'name')) {
                $table->string('name');
            }
            
            if (!Schema::hasColumn('payment_methods', 'code')) {
                $table->string('code')->unique();
            }
            
            if (!Schema::hasColumn('payment_methods', 'description')) {
                $table->text('description')->nullable();
            }
            
            if (!Schema::hasColumn('payment_methods', 'icon')) {
                $table->string('icon')->nullable();
            }
            
            if (!Schema::hasColumn('payment_methods', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            if (!Schema::hasColumn('payment_methods', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
            
            if (!Schema::hasColumn('payment_methods', 'config')) {
                $table->json('config')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'code',
                'description',
                'icon',
                'is_active',
                'sort_order',
                'config',
            ]);
        });
    }
};
