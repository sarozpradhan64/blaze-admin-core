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
        Schema::dropIfExists('menu_items');

        Schema::table('menus', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('menus')->nullOnDelete();
            $table->string('type')->default('custom_url')->after('title'); // assuming name will be renamed to title
            $table->unsignedBigInteger('reference_id')->nullable()->after('type');
            $table->string('url')->nullable()->after('reference_id');
            $table->integer('sort_order')->default(0)->after('url');
            $table->boolean('is_editable')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse
        Schema::table('menus', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'type', 'reference_id', 'url', 'sort_order', 'is_editable']);
        });
    }
};
