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
        Schema::table('service_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('service_categories')->nullOnDelete();
            $table->longText('description')->nullable()->change();
            $table->string('thumbnail', 500)->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'thumbnail']);
            $table->text('description')->nullable()->change();
        });
    }
};
