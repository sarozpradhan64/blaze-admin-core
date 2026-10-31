<?php

use Blaze\AdminCore\AdminCoreConfiguration;

return [
    'configuration' => AdminCoreConfiguration::class,

    /**
     * Optional custom homepage section definitions.
     * When left null, defaults to AdminCoreConfiguration::homepageSectionDefinitions().
     */
    'homepage_sections' => null,

    /**
     * Optional custom static routes for menu links.
     * When left null, defaults to AdminCoreConfiguration::staticRoutes().
     */
    'static_routes' => null,
];
