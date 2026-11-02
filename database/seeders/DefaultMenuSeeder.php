<?php

namespace Blaze\AdminCore\Database\Seeders;

use Blaze\AdminCore\Models\Menu;
use Blaze\AdminCore\Models\Page;
use Blaze\AdminCore\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class DefaultMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. NAVBAR MENUS (Top Navigation)
        // ----------------------------------------------------

        // Blogs
        Menu::firstOrCreate(
            ['title' => 'Blogs', 'parent_id' => null, 'location' => 'navbar'],
            ['type' => 'custom_url', 'url' => '/blogs', 'sort_order' => 1, 'is_editable' => false, 'is_deletable' => false]
        );

        // Gallery
        Menu::firstOrCreate(
            ['title' => 'Gallery', 'parent_id' => null, 'location' => 'navbar'],
            ['type' => 'custom_url', 'url' => '/gallery', 'sort_order' => 2, 'is_editable' => false, 'is_deletable' => false]
        );

        // About Us
        $aboutPage = Page::where('slug', 'about-us')->first();
        $aboutMenu = Menu::firstOrCreate(
            ['title' => 'About Us', 'parent_id' => null, 'location' => 'navbar'],
            [
                'type' => $aboutPage ? 'page' : 'custom_url',
                'url' => $aboutPage ? null : '/about-us',
                'reference_id' => $aboutPage ? $aboutPage->id : null,
                'sort_order' => 3,
                'is_editable' => true,
                'is_deletable' => true,
            ]
        );

        // Add children to About Us
        Menu::firstOrCreate(
            ['title' => 'About Neepa Adventure', 'parent_id' => $aboutMenu->id],
            ['location' => 'navbar', 'subtitle' => 'Our story', 'icon' => 'users', 'type' => 'custom_url', 'url' => '/about-us/story', 'sort_order' => 1]
        );

        Menu::firstOrCreate(
            ['title' => 'Our Team', 'parent_id' => $aboutMenu->id],
            ['location' => 'navbar', 'subtitle' => 'Meet our experts', 'icon' => 'users-2', 'type' => 'custom_url', 'url' => '/about-us/team', 'sort_order' => 2]
        );

        Menu::firstOrCreate(
            ['title' => 'Reviews', 'parent_id' => $aboutMenu->id],
            ['location' => 'navbar', 'subtitle' => 'What our customers say', 'icon' => 'star', 'type' => 'custom_url', 'url' => '/about-us/reviews', 'sort_order' => 3]
        );

        Menu::firstOrCreate(
            ['title' => 'Careers', 'parent_id' => $aboutMenu->id],
            ['location' => 'navbar', 'subtitle' => 'Join our team', 'icon' => 'briefcase', 'type' => 'custom_url', 'url' => '/about-us/careers', 'sort_order' => 4]
        );

        // Contact Us
        Menu::firstOrCreate(
            ['title' => 'Contact Us', 'parent_id' => null, 'location' => 'navbar'],
            ['type' => 'custom_url', 'url' => '/contact', 'sort_order' => 4, 'is_editable' => false, 'is_deletable' => false]
        );

        // ----------------------------------------------------
        // 2. FOOTER MENUS (Footer Columns & Sub-items)
        // ----------------------------------------------------

        // Column 1: Himalayan Expeditions
        $himalayanMenu = Menu::updateOrCreate(
            [
                'title' => 'Himalayan Expeditions',
                'parent_id' => null,
                'location' => 'footer',
            ],
            [
                'url' => '#',
                'type' => 'custom_url',
                'sort_order' => 1,
                'status' => true,
                'is_editable' => true,
                'is_deletable' => true,
            ]
        );

        $trekkingCat = ServiceCategory::where('slug', 'trekking')->first();
        $toursCat = ServiceCategory::where('slug', 'tours-experiences')->first();
        $adventuresCat = ServiceCategory::where('slug', 'adventures')->first();

        $himalayanItems = [
            [
                'title' => 'Nepal Trekking Catalog',
                'type' => 'custom_url',
                'url' => '/treks',
                'reference_id' => null,
                'sort_order' => 1,
            ],
            [
                'title' => 'Guaranteed Departures',
                'type' => 'custom_url',
                'url' => '/schedules',
                'reference_id' => null,
                'sort_order' => 2,
            ],
            [
                'title' => 'Classic High Passes',
                'type' => $trekkingCat ? 'service_category' : 'custom_url',
                'url' => $trekkingCat ? null : '/services/trekking',
                'reference_id' => $trekkingCat?->id,
                'sort_order' => 3,
            ],
            [
                'title' => 'Tours & Experiences',
                'type' => $toursCat ? 'service_category' : 'custom_url',
                'url' => $toursCat ? null : '/services/tours-experiences',
                'reference_id' => $toursCat?->id,
                'sort_order' => 4,
            ],
            [
                'title' => 'Peak Climbing & Alpine',
                'type' => $adventuresCat ? 'service_category' : 'custom_url',
                'url' => $adventuresCat ? null : '/services/adventures',
                'reference_id' => $adventuresCat?->id,
                'sort_order' => 5,
            ],
            [
                'title' => 'Plan Custom Itinerary',
                'type' => 'custom_url',
                'url' => '/plan-your-trek',
                'reference_id' => null,
                'sort_order' => 6,
            ],
            [
                'title' => 'Search Himalayan Routes',
                'type' => 'custom_url',
                'url' => '/search',
                'reference_id' => null,
                'sort_order' => 7,
            ],
        ];

        foreach ($himalayanItems as $item) {
            Menu::updateOrCreate(
                [
                    'parent_id' => $himalayanMenu->id,
                    'title' => $item['title'],
                ],
                [
                    'location' => 'footer',
                    'type' => $item['type'],
                    'url' => $item['url'],
                    'reference_id' => $item['reference_id'],
                    'sort_order' => $item['sort_order'],
                    'status' => true,
                    'is_editable' => true,
                    'is_deletable' => true,
                ]
            );
        }

        // Column 2: Company & Community
        $companyMenu = Menu::updateOrCreate(
            [
                'title' => 'Company & Community',
                'parent_id' => null,
                'location' => 'footer',
            ],
            [
                'url' => '#',
                'type' => 'custom_url',
                'sort_order' => 2,
                'status' => true,
                'is_editable' => true,
                'is_deletable' => true,
            ]
        );

        $companyItems = [
            [
                'title' => 'Our Story',
                'type' => 'custom_url',
                'url' => '/about-us',
                'sort_order' => 1,
            ],
            [
                'title' => 'Mountain Guides',
                'type' => 'custom_url',
                'url' => '/about-us/team',
                'sort_order' => 2,
            ],
            [
                'title' => 'Client Reviews',
                'type' => 'custom_url',
                'url' => '/about-us/reviews',
                'sort_order' => 3,
            ],
            [
                'title' => 'Write a Review',
                'type' => 'custom_url',
                'url' => '/write-review',
                'sort_order' => 4,
            ],
            [
                'title' => 'Travel Guides & Blog',
                'type' => 'custom_url',
                'url' => '/blogs',
                'sort_order' => 5,
            ],
            [
                'title' => 'Trek Case Studies',
                'type' => 'custom_url',
                'url' => '/projects',
                'sort_order' => 6,
            ],
            [
                'title' => 'Photo Gallery',
                'type' => 'custom_url',
                'url' => '/gallery',
                'sort_order' => 7,
            ],
            [
                'title' => 'Careers',
                'type' => 'custom_url',
                'url' => '/about-us/careers',
                'sort_order' => 8,
            ],
            [
                'title' => 'Contact Us',
                'type' => 'custom_url',
                'url' => '/contact',
                'sort_order' => 9,
            ],
        ];

        foreach ($companyItems as $item) {
            Menu::updateOrCreate(
                [
                    'parent_id' => $companyMenu->id,
                    'title' => $item['title'],
                ],
                [
                    'location' => 'footer',
                    'type' => $item['type'],
                    'url' => $item['url'],
                    'sort_order' => $item['sort_order'],
                    'status' => true,
                    'is_editable' => true,
                    'is_deletable' => true,
                ]
            );
        }

        Menu::flushCache();
    }
}
