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
}
