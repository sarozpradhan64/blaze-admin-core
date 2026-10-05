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
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('is_deletable')->default(true)->after('status');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->boolean('is_editable')->default(true)->after('sort_order');
            $table->boolean('is_deletable')->default(true)->after('is_editable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['is_editable', 'is_deletable']);
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('is_deletable');
        });
    }
};
