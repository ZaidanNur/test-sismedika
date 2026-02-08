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
        Schema::table('food', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('name');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['name']);
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
