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
    public function index(Request $request, AdminCoreConfiguration $config)
    {
        $menus = Menu::whereNull('parent_id')->orderBy('sort_order')->paginate($request->get('per_page', 50));
        $missingRoutes = $config->missingRootRoutes(Menu::all());

        return view('admin-core::menus.index', compact('menus', 'missingRoutes'));
    }

    public function create(AdminCoreConfiguration $config)
    {
        $staticRoutes = $config->staticRoutes();

        return view('admin-core::menus.form', compact('staticRoutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        $validated['status'] = $request->has('status');
        $validated['type'] = 'custom_url';
        $validated['url'] = ! empty($validated['url']) ? $validated['url'] : '#';

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu, AdminCoreConfiguration $config)
    {
        $menu->load(['children.children.children.children']); // Load nested items for builder

        $pages = Page::where('status', true)->orderBy('title')->get(['id', 'title']);
        $serviceCategories = ServiceCategory::where('status', true)
            ->with(['parent', 'children' => fn ($q) => $q->where('status', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get(['id', 'name', 'parent_id']);

        $services = Service::where('status', true)
            ->with(['category.parent'])
            ->orderBy('sort_order')
            ->get(['id', 'title', 'service_category_id', 'slug']);

        $staticRoutes = $config->staticRoutes();

        return view('admin-core::menus.form', compact('menu', 'pages', 'serviceCategories', 'services', 'staticRoutes'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        $validated['status'] = $request->has('status');
        if ($request->has('url')) {
            $validated['url'] = ! empty($validated['url']) ? $validated['url'] : '#';
            if (empty($menu->type)) {
                $validated['type'] = 'custom_url';
            }
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if (! $menu->is_deletable) {
            return redirect()->route('admin.menus.index')->with('error', 'This menu cannot be deleted.');
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}
