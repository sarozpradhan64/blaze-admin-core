<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\AdminCoreConfiguration;
use Illuminate\View\View;

class HelpController extends Controller
{
    /**
     * Display the comprehensive Admin Portal Help & Documentation page.
     */
    public function index(AdminCoreConfiguration $configuration): View
    {
        return view('admin-core::help.index', [
            'configuration' => $configuration,
        ]);
    }
}
