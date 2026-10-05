<?php

namespace Blaze\AdminCore\Database\Seeders;

use Blaze\AdminCore\Models\Menu;
use Blaze\AdminCore\Models\Page;
use Illuminate\Database\Seeder;

class DefaultMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Blogs
        Menu::firstOrCreate(
            ['title' => 'Blogs', 'parent_id' => null],
            ['type' => 'custom_url', 'url' => '/blogs', 'sort_order' => 1, 'is_editable' => false, 'is_deletable' => false]
        );

        // Gallery
        Menu::firstOrCreate(
            ['title' => 'Gallery', 'parent_id' => null],
            ['type' => 'custom_url', 'url' => '/gallery', 'sort_order' => 2, 'is_editable' => false, 'is_deletable' => false]
        );

        // About Us - Need a page for this if it exists, otherwise custom url
        $aboutPage = Page::where('slug', 'about-us')->first();
        $aboutMenu = Menu::firstOrCreate(
            ['title' => 'About Us', 'parent_id' => null],
            [
                'type' => $aboutPage ? 'page' : 'custom_url',
                'url' => $aboutPage ? null : '/about-us',
                'reference_id' => $aboutPage ? $aboutPage->id : null,
                'sort_order' => 3,
                'is_editable' => true,
                'is_deletable' => true
            ]
        );

        // Add children to About Us
        Menu::firstOrCreate(
            ['title' => 'About Neepa Adventure', 'parent_id' => $aboutMenu->id],
            ['subtitle' => 'Our story', 'icon' => 'users', 'type' => 'custom_url', 'url' => '/about-us/story', 'sort_order' => 1]
        );

        Menu::firstOrCreate(
            ['title' => 'Our Team', 'parent_id' => $aboutMenu->id],
            ['subtitle' => 'Meet our experts', 'icon' => 'users-2', 'type' => 'custom_url', 'url' => '/about-us/team', 'sort_order' => 2]
        );

        Menu::firstOrCreate(
            ['title' => 'Reviews', 'parent_id' => $aboutMenu->id],
            ['subtitle' => 'What our customers say', 'icon' => 'star', 'type' => 'custom_url', 'url' => '/about-us/reviews', 'sort_order' => 3]
        );

        Menu::firstOrCreate(
            ['title' => 'Careers', 'parent_id' => $aboutMenu->id],
            ['subtitle' => 'Join our team', 'icon' => 'briefcase', 'type' => 'custom_url', 'url' => '/about-us/careers', 'sort_order' => 4]
        );

        // Contact Us
        Menu::firstOrCreate(
            ['title' => 'Contact Us', 'parent_id' => null],
            ['type' => 'custom_url', 'url' => '/contact', 'sort_order' => 4, 'is_editable' => false, 'is_deletable' => false]
        );
    }
}
