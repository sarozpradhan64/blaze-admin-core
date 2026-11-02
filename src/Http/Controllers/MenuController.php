<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\AdminCoreConfiguration;
use Blaze\AdminCore\Models\Menu;
use Blaze\AdminCore\Models\Page;
use Blaze\AdminCore\Models\Service;
use Blaze\AdminCore\Models\ServiceCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $currentLocation = $request->get('location', 'navbar');
        if (! in_array($currentLocation, ['navbar', 'footer'])) {
            $currentLocation = 'navbar';
        }

        $navbarCount = Menu::whereNull('parent_id')->where('location', 'navbar')->count();
        $footerCount = Menu::whereNull('parent_id')->where('location', 'footer')->count();

        $menus = Menu::whereNull('parent_id')
            ->where('location', $currentLocation)
            ->orderBy('sort_order')
            ->paginate($request->get('per_page', 50))
            ->appends(['location' => $currentLocation]);

        return view('admin-core::menus.index', compact('menus', 'currentLocation', 'navbarCount', 'footerCount'));
    }

    public function create(Request $request, AdminCoreConfiguration $config)
    {
        $staticRoutes = $config->staticRoutes();
        $selectedLocation = $request->get('location', 'navbar');
        $footerMenusFull = Menu::whereNull('parent_id')->where('location', 'footer')->count() >= 2;

        if ($selectedLocation === 'footer' && $footerMenusFull) {
            return redirect()->route('admin.menus.index', ['location' => 'footer'])
                ->with('error', 'Only 2 parent menus are allowed for the footer. You can edit existing footer menus or add sub-menu items to them.');
        }

        return view('admin-core::menus.form', compact('staticRoutes', 'selectedLocation', 'footerMenusFull'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'in:navbar,footer'],
            'url' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        if ($validated['location'] === 'footer') {
            $footerCount = Menu::whereNull('parent_id')->where('location', 'footer')->count();
            if ($footerCount >= 2) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['location' => 'Only 2 parent menus are allowed for the footer. You can add items inside existing footer menus.'])
                    ->with('error', 'Only 2 parent menus are allowed for the footer.');
            }
        }

        $validated['status'] = $request->has('status');
        $validated['type'] = 'custom_url';
        $validated['url'] = ! empty($validated['url']) ? $validated['url'] : '#';

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu, AdminCoreConfiguration $config)
    {
        $menu->load(['children.children.children.children']); // Load nested items for builder

        $allMenuItems = collect();
        $collectDescendants = function ($parent) use (&$collectDescendants, &$allMenuItems) {
            foreach ($parent->children as $child) {
                $allMenuItems->push($child);
                if ($child->relationLoaded('children')) {
                    $collectDescendants($child);
                }
            }
        };
        $collectDescendants($menu);

        $usedCategoryIds = $allMenuItems->where('type', 'service_category')->pluck('reference_id')->filter()->values()->all();
        $usedServiceIds = $allMenuItems->where('type', 'service')->pluck('reference_id')->filter()->values()->all();

        $pages = Page::where('status', true)->orderBy('title')->get(['id', 'title']);
        $serviceCategories = ServiceCategory::where('status', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'parent_id']);

        $services = Service::where('status', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'service_category_id', 'slug']);

        $staticRoutes = $config->staticRoutes();
        $footerMenusFull = Menu::whereNull('parent_id')
            ->where('location', 'footer')
            ->where('id', '!=', $menu->id)
            ->count() >= 2;

        return view('admin-core::menus.form', compact(
            'menu',
            'pages',
            'serviceCategories',
            'services',
            'staticRoutes',
            'usedCategoryIds',
            'usedServiceIds',
            'footerMenusFull'
        ));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'in:navbar,footer'],
            'url' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        if ($validated['location'] === 'footer' && $menu->location !== 'footer') {
            $otherFooterCount = Menu::whereNull('parent_id')
                ->where('location', 'footer')
                ->where('id', '!=', $menu->id)
                ->count();

            if ($otherFooterCount >= 2) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['location' => 'Only 2 parent menus are allowed for the footer.'])
                    ->with('error', 'Only 2 parent menus are allowed for the footer.');
            }
        }

        $validated['status'] = $request->has('status');
        if ($request->has('url')) {
            $validated['url'] = ! empty($validated['url']) ? $validated['url'] : '#';
            if (empty($menu->type)) {
                $validated['type'] = 'custom_url';
            }
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index', ['location' => $menu->location])->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if (! $menu->is_deletable) {
            return redirect()->route('admin.menus.index')->with('error', 'This menu cannot be deleted.');
        }

        $location = $menu->location ?? 'navbar';
        $menu->delete();

        return redirect()->route('admin.menus.index', ['location' => $location])->with('success', 'Menu deleted successfully.');
    }
}
