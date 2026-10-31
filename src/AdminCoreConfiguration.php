<?php

namespace Blaze\AdminCore;

use Blaze\AdminCore\Support\ServiceFormField;

class AdminCoreConfiguration
{
    /**
     * @return array<string, bool>
     */
    public function modules(): array
    {
        return [
            'users' => true,
            'profile' => true,
            'settings' => true,
            'services' => true,
            'projects' => true,
            'pages' => true,
            'menus' => true,
            'blog' => true,
            'enquiries' => true,
            'contact-messages' => true,
            'testimonials' => true,
            'team-members' => true,
            'gallery' => true,
            'downloads' => true,
            'company' => true,
            'company-info' => true,
            'affiliations' => true,
            'payment-methods' => true,
            'legal-documents' => true,
            'careers' => true,
            'tags' => true,
            'help' => true,
        ];
    }

    public function enabled(string $module): bool
    {
        return $this->modules()[$module] ?? false;
    }

    /**
     * @return array<string, bool>
     */
    public function features(): array
    {
        return [
            'hero_highlighted_text' => false,
        ];
    }

    public function featureEnabled(string $feature): bool
    {
        return $this->features()[$feature] ?? false;
    }

    /**
     * Extra site-specific fields to inject into the service create / edit form.
     *
     * Override this in the project's AdminCoreConfiguration subclass and return
     * an array of ServiceFormField instances describing each additional column.
     *
     * @return ServiceFormField[]
     */
    public function serviceFormFields(): array
    {
        return [];
    }

    /**
     * Extra site-specific fields to inject into the service feature create / edit form.
     *
     * @return ServiceFormField[]
     */
    public function serviceFeatureFormFields(): array
    {
        return [];
    }

    /**
     * Define custom submenu items to inject into existing sidebar modules.
     *
     * Structure: ['module_key' => [['label' => 'Name', 'route' => 'route.name', 'activeRoute' => 'route.*']]]
     */
    public function customSidebarItems(): array
    {
        return [];
    }

    /**
     * Predefined / standard homepage section definitions that can be added and managed.
     *
     * Override this in the project's AdminCoreConfiguration subclass and return
     * an array of section definitions, or define 'homepage_sections' in config/admin-core.php.
     *
     * @return array<int, array{
     *     type: string,
     *     name: string,
     *     title: string,
     *     subtitle?: string,
     *     button_text?: string,
     *     button_url?: string,
     *     badgeClass?: string
     * }>
     */
    public function homepageSectionDefinitions(): array
    {
        return config('admin-core.homepage_sections', []);
    }

    /**
     * Available static frontend routes for menu URL suggestions.
     *
     * Override this in the project's AdminCoreConfiguration subclass, or define
     * 'static_routes' in config/admin-core.php.
     *
     * @return array<int, array{path: string, label: string}>
     */
    public function staticRoutes(): array
    {
        $configured = config('admin-core.static_routes');
        if (is_array($configured)) {
            return $configured;
        }

        return [
            ['path' => '/', 'label' => 'Home (/)'],
            ['path' => '/about-us', 'label' => 'About Us (/about-us)'],
            ['path' => '/contact', 'label' => 'Contact Us (/contact)'],
            ['path' => '/blogs', 'label' => 'Blogs (/blogs)'],
            ['path' => '/gallery', 'label' => 'Gallery (/gallery)'],
            ['path' => '/projects', 'label' => 'Projects (/projects)'],
            ['path' => '/services', 'label' => 'Services (/services)'],
        ];
    }

    /**
     * Compute static root routes that are not currently linked to any top-level menu.
     *
     * @param  iterable<\Blaze\AdminCore\Models\Menu>|null  $existingMenus
     * @return array<int, array{path: string, label: string, full_label: string}>
     */
    public function missingRootRoutes(?iterable $existingMenus = null): array
    {
        $menus = $existingMenus ?? \Blaze\AdminCore\Models\Menu::all();

        $existingUrls = collect($menus)->pluck('url')->filter()->map(function ($u) {
            return trim(strtolower($u), '/');
        })->all();

        $existingTitleSlugs = collect($menus)->pluck('title')->filter()->map(function ($t) {
            return \Illuminate\Support\Str::slug($t);
        })->all();

        return collect($this->staticRoutes())
            ->filter(function ($route) use ($existingUrls, $existingTitleSlugs) {
                $path = trim(strtolower($route['path'] ?? ''), '/');

                // Ignore home root and non-root sub-paths (must be single segment)
                if (empty($path) || str_contains($path, '/')) {
                    return false;
                }

                // Check direct URL match
                if (in_array($path, $existingUrls, true)) {
                    return false;
                }

                // Check title slug match
                foreach ($existingTitleSlugs as $slug) {
                    if ($slug === $path || str_contains($slug, $path) || str_contains($path, $slug)) {
                        return false;
                    }
                }

                return true;
            })
            ->map(function ($route) {
                // Strip trailing "(...)" from label to get clean title (e.g. "Projects (/projects)" -> "Projects")
                $cleanTitle = trim(preg_replace('/\s*\(.*?\)$/', '', $route['label'] ?? ''));
                if (empty($cleanTitle)) {
                    $cleanTitle = ucfirst(trim($route['path'], '/'));
                }

                return [
                    'path' => $route['path'],
                    'label' => $cleanTitle,
                    'full_label' => $route['label'],
                ];
            })
            ->values()
            ->all();
    }
}

