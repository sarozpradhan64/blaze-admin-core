<?php

namespace Blaze\AdminCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    /**
     * Clear application cache and compiled views.
     */
    public function clear(): RedirectResponse
    {
        Cache::flush();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Application cache cleared successfully.');
    }
}
