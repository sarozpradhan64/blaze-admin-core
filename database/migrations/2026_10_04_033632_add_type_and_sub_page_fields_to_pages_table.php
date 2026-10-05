<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('type')->default('page')->after('id');
            $table->string('sub_title')->nullable()->after('title');
            $table->text('sub_text')->nullable()->after('sub_title');
        });

        // Set existing records to 'blog' assuming they are blogs
        DB::table('pages')->update(['type' => 'blog']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['type', 'sub_title', 'sub_text']);
        });
    }
};
