<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\Menu;
use Blaze\AdminCore\Models\Page;
use Blaze\AdminCore\Models\ServiceCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::whereNull('parent_id')->orderBy('sort_order')->paginate($request->get('per_page', 50));

        return view('admin-core::menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin-core::menus.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        $validated['status'] = $request->has('status');

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $menu->load(['children.children.children.children']); // Load nested items for builder

        $pages = Page::where('status', true)->get(['id', 'title']);
        $serviceCategories = ServiceCategory::where('status', true)->get(['id', 'name']);

        return view('admin-core::menus.form', compact('menu', 'pages', 'serviceCategories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['nullable'],
        ]);

        $validated['status'] = $request->has('status');

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
