<?php

namespace Blaze\AdminCore;

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
            'blog' => true,
            'enquiries' => true,
            'contact-messages' => true,
            'testimonials' => true,
            'team-members' => true,
            'gallery' => true,
            'downloads' => true,
            'company-info' => true,
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
}
