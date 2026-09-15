<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image', 500)->nullable();
            $table->boolean('status');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image', 500)->nullable();
            $table->boolean('status');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('featured_image', 500)->nullable();
            $table->string('icon')->nullable();
            $table->boolean('status');
            $table->boolean('is_featured');
            $table->integer('sort_order');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('service_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('status');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name')->nullable();
            $table->string('location')->nullable();
            $table->string('project_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('project_status', ['upcoming', 'ongoing', 'completed']);
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('featured_image', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->boolean('is_featured');
            $table->boolean('status');
            $table->integer('sort_order');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('image', 500);
            $table->string('alt_text')->nullable();
            $table->string('caption', 500)->nullable();
            $table->boolean('is_featured');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('project_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('title')->nullable();
            $table->string('video_url', 500);
            $table->enum('video_type', ['youtube', 'vimeo', 'uploaded']);
            $table->string('thumbnail', 500)->nullable();
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('project_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('label');
            $table->string('value');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->text('message');
            $table->string('image', 500)->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->boolean('status');
            $table->boolean('is_featured');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('department')->nullable();
            $table->text('bio')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('facebook_url', 500)->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->boolean('status');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained('gallery_albums');
            $table->string('title')->nullable();
            $table->enum('type', ['image', 'video']);
            $table->string('file_path', 500)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption', 500)->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['company_profile', 'brochure', 'certificate', 'catalogue', 'other']);
            $table->string('file_path', 500);
            $table->bigInteger('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->bigInteger('download_count')->default(0);
            $table->boolean('status');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('company')->nullable();
            $table->string('subject')->nullable();
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->text('message');
            $table->string('source', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('status', ['new', 'in_progress', 'contacted', 'converted', 'closed', 'spam']);
            $table->enum('priority', ['low', 'normal', 'high', 'urgent']);
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied', 'closed', 'spam']);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_information', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('address');
            $table->string('phone', 50);
            $table->string('secondary_phone', 50)->nullable();
            $table->string('email');
            $table->string('secondary_email')->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url', 1000)->nullable();
            $table->text('working_hours')->nullable();
            $table->timestamps();
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 50);
            $table->string('url', 500);
            $table->string('icon')->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type', 50);
            $table->string('group', 100);
            $table->timestamps();
        });

        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_type', 100);
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url', 500)->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('seoable_type');
            $table->bigInteger('seoable_id');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 1000)->nullable();
            $table->string('robots')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_type', 50)->nullable();
            $table->string('twitter_card', 50)->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 500)->nullable();
            $table->longText('schema_markup')->nullable();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('mime_type', 100);
            $table->bigInteger('file_size');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption', 500)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('spam_logs', function (Blueprint $table) {
            $table->id();
            $table->string('form_type', 100);
            $table->string('ip_address', 45);
            $table->string('email')->nullable();
            $table->string('reason');
            $table->decimal('score', 3, 2)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('action', 100);
            $table->string('auditable_type')->nullable();
            $table->bigInteger('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('project_service', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('service_id')->constrained('services');
            $table->primary(['project_id', 'service_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('project_service');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('spam_logs');
        Schema::dropIfExists('media');
        Schema::dropIfExists('seo_metadata');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('contact_information');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('project_statistics');
        Schema::dropIfExists('project_videos');
        Schema::dropIfExists('project_images');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_categories');
        Schema::dropIfExists('service_features');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
        Schema::dropIfExists('pages');
    }
};
