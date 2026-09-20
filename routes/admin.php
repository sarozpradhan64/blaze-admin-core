<?php

use Illuminate\Support\Facades\Route;
use Blaze\AdminCore\Http\Controllers\AuthController;
use Blaze\AdminCore\Http\Controllers\ProfileController;
use Blaze\AdminCore\Http\Controllers\UserController;
use Blaze\AdminCore\Http\Controllers\WebsiteSettingController;
use Blaze\AdminCore\Http\Controllers\SeoSettingController;
use Blaze\AdminCore\Http\Controllers\ReorderController;

Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {

    // Guest-only auth routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Protected core admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/', function () {
            return view('admin-core::dashboard');
        })->name('dashboard');

        // Settings (unified tabbed page)
        Route::get('settings', [WebsiteSettingController::class, 'index'])->name('settings.index');
        Route::put('settings/homepage', [WebsiteSettingController::class, 'updateHomepage'])->name('settings.homepage.update');
        Route::put('settings/about', [WebsiteSettingController::class, 'updateAbout'])->name('settings.about.update');
        Route::put('settings/seo', [WebsiteSettingController::class, 'updateSeo'])->name('settings.seo.update');

        Route::resource('users', UserController::class)->except(['show']);
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

        // Admin Services
        Route::resource('services', Blaze\AdminCore\Http\Controllers\ServiceController::class);
        Route::resource('service-categories', Blaze\AdminCore\Http\Controllers\ServiceCategoryController::class);
        Route::resource('service-features', Blaze\AdminCore\Http\Controllers\ServiceFeatureController::class);

        // Admin Projects
        Route::resource('projects', Blaze\AdminCore\Http\Controllers\ProjectController::class);
        Route::resource('project-categories', Blaze\AdminCore\Http\Controllers\ProjectCategoryController::class);
        Route::resource('project-images', Blaze\AdminCore\Http\Controllers\ProjectImageController::class);
        Route::resource('project-videos', Blaze\AdminCore\Http\Controllers\ProjectVideoController::class);
        Route::resource('project-statistics', Blaze\AdminCore\Http\Controllers\ProjectStatisticController::class);

        // Blog
        Route::resource('blogs', Blaze\AdminCore\Http\Controllers\BlogController::class);
        Route::resource('blog-categories', Blaze\AdminCore\Http\Controllers\BlogCategoryController::class);

        // Core shared resources
        Route::resource('contact-messages', Blaze\AdminCore\Http\Controllers\ContactMessageController::class)->except(['create', 'store', 'edit']);
        Route::resource('enquiries', Blaze\AdminCore\Http\Controllers\EnquiryController::class)->except(['create', 'store', 'edit']);
        Route::resource('testimonials', Blaze\AdminCore\Http\Controllers\TestimonialController::class);
        Route::resource('team-members', Blaze\AdminCore\Http\Controllers\TeamMemberController::class);
        Route::resource('gallery-albums', Blaze\AdminCore\Http\Controllers\GalleryAlbumController::class);
        Route::resource('gallery-items', Blaze\AdminCore\Http\Controllers\GalleryItemController::class);
        Route::resource('downloads', Blaze\AdminCore\Http\Controllers\DownloadController::class);

        // Company Info (Contact Information + Social Links)
        Route::get('company-info', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'index'])->name('company-info.index');
        Route::put('company-info/contact', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'updateContact'])->name('company-info.contact.update');
        Route::post('company-info/social-links', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'storeSocialLink'])->name('company-info.social-links.store');
        Route::put('company-info/social-links/{socialLink}', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'updateSocialLink'])->name('company-info.social-links.update');
        Route::delete('company-info/social-links/{socialLink}', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'destroySocialLink'])->name('company-info.social-links.destroy');
        Route::post('company-info/social-links/reorder', [Blaze\AdminCore\Http\Controllers\CompanyInfoController::class, 'reorderSocialLinks'])->name('company-info.social-links.reorder');

        // Universal drag-to-reorder endpoint
        Route::post('reorder/{resource}', ReorderController::class)->name('reorder');
    });
});
