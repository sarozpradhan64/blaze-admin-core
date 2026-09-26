<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });

        Schema::create('faqables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->morphs('faqable');
            $table->timestamps();

            $table->unique(['faq_id', 'faqable_id', 'faqable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqables');
        Schema::dropIfExists('faqs');
    }
};
