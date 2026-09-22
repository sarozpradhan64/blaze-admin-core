<?php

use Blaze\AdminCore\AdminCoreConfiguration;
use Blaze\AdminCore\Http\Controllers\AuthController;
use Blaze\AdminCore\Http\Controllers\BlogCategoryController;
use Blaze\AdminCore\Http\Controllers\BlogController;
use Blaze\AdminCore\Http\Controllers\CompanyInfoController;
use Blaze\AdminCore\Http\Controllers\ContactMessageController;
use Blaze\AdminCore\Http\Controllers\DownloadController;
use Blaze\AdminCore\Http\Controllers\EnquiryController;
use Blaze\AdminCore\Http\Controllers\GalleryAlbumController;
use Blaze\AdminCore\Http\Controllers\GalleryItemController;
use Blaze\AdminCore\Http\Controllers\ProfileController;
use Blaze\AdminCore\Http\Controllers\ProjectCategoryController;
use Blaze\AdminCore\Http\Controllers\ProjectController;
use Blaze\AdminCore\Http\Controllers\ProjectImageController;
use Blaze\AdminCore\Http\Controllers\ProjectStatisticController;
use Blaze\AdminCore\Http\Controllers\ProjectVideoController;
use Blaze\AdminCore\Http\Controllers\ReorderController;
use Blaze\AdminCore\Http\Controllers\ServiceCategoryController;
use Blaze\AdminCore\Http\Controllers\ServiceController;
use Blaze\AdminCore\Http\Controllers\ServiceFeatureController;
use Blaze\AdminCore\Http\Controllers\TagController;
use Blaze\AdminCore\Http\Controllers\TeamMemberController;
use Blaze\AdminCore\Http\Controllers\TestimonialController;
use Blaze\AdminCore\Http\Controllers\UserController;
use Blaze\AdminCore\Http\Controllers\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {
    // Guest-only auth routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Protected core admin routes
    Route::middleware('auth')->group(function () {
        $configuration = app(AdminCoreConfiguration::class);

        Route::get('/', function () {
            return view('admin-core::dashboard');
        })->name('dashboard');

        // Settings (unified tabbed page)
        if ($configuration->enabled('settings')) {
            Route::get('settings', [WebsiteSettingController::class, 'index'])->name('settings.index');
            Route::put('settings/homepage', [WebsiteSettingController::class, 'updateHomepage'])->name('settings.homepage.update');
            Route::put('settings/about', [WebsiteSettingController::class, 'updateAbout'])->name('settings.about.update');
            Route::put('settings/system', [WebsiteSettingController::class, 'updateSystem'])->name('settings.system.update');
            Route::put('settings/seo', [WebsiteSettingController::class, 'updateSeo'])->name('settings.seo.update');
        }

        if ($configuration->enabled('users')) {
            Route::resource('users', UserController::class)->except(['show']);
        }

        if ($configuration->enabled('profile')) {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        }

        // Admin Services
        if ($configuration->enabled('services')) {
            Route::resource('services', ServiceController::class);
            Route::resource('service-categories', ServiceCategoryController::class);
            Route::resource('service-features', ServiceFeatureController::class);
        }

        // Admin Projects
        if ($configuration->enabled('projects')) {
            Route::resource('projects', ProjectController::class);
            Route::resource('project-categories', ProjectCategoryController::class);
            Route::resource('project-images', ProjectImageController::class);
            Route::resource('project-videos', ProjectVideoController::class);
            Route::resource('project-statistics', ProjectStatisticController::class);
        }

        // Blog
        if ($configuration->enabled('blog')) {
            Route::resource('blogs', BlogController::class);
            Route::resource('blog-categories', BlogCategoryController::class);
        }

        // Core shared resources
        if ($configuration->enabled('contact-messages')) {
            Route::resource('contact-messages', ContactMessageController::class)->except(['create', 'store', 'edit']);
        }
        if ($configuration->enabled('enquiries')) {
            Route::resource('enquiries', EnquiryController::class)->except(['create', 'store', 'edit']);
        }
        if ($configuration->enabled('testimonials')) {
            Route::resource('testimonials', TestimonialController::class);
        }
        if ($configuration->enabled('team-members')) {
            Route::resource('team-members', TeamMemberController::class);
        }
        if ($configuration->enabled('gallery')) {
            Route::resource('gallery-albums', GalleryAlbumController::class);
            Route::resource('gallery-items', GalleryItemController::class);
        }
        if ($configuration->enabled('downloads')) {
            Route::resource('downloads', DownloadController::class);
        }

        // Company Info (Contact Information + Social Links)
        if ($configuration->enabled('company-info')) {
            Route::get('company-info', [CompanyInfoController::class, 'index'])->name('company-info.index');
            Route::put('company-info/contact', [CompanyInfoController::class, 'updateContact'])->name('company-info.contact.update');
            Route::post('company-info/social-links', [CompanyInfoController::class, 'storeSocialLink'])->name('company-info.social-links.store');
            Route::put('company-info/social-links/{socialLink}', [CompanyInfoController::class, 'updateSocialLink'])->name('company-info.social-links.update');
            Route::delete('company-info/social-links/{socialLink}', [CompanyInfoController::class, 'destroySocialLink'])->name('company-info.social-links.destroy');
            Route::post('company-info/social-links/reorder', [CompanyInfoController::class, 'reorderSocialLinks'])->name('company-info.social-links.reorder');
        }

        // Universal drag-to-reorder endpoint
        Route::post('reorder/{resource}', ReorderController::class)->name('reorder');
        Route::get('tags', [TagController::class, 'index'])->name('tags.index');
        Route::get('tags/manage', [TagController::class, 'manage'])->name('tags.manage');
        Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
        Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');
        Route::post('tags', [TagController::class, 'store'])->name('tags.store');
        Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    });
});
