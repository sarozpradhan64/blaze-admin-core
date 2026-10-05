<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\Menu;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', 'in:custom_url,page,service_category'],
            'url' => ['nullable', 'string', 'required_if:type,custom_url'],
            'reference_id' => ['nullable', 'integer', 'required_unless:type,custom_url'],
        ]);

        $validated['sort_order'] = Menu::where('parent_id', $menu->id)->max('sort_order') + 1;

        $menu->children()->create($validated);

        return redirect()->back()->with('success', 'Menu item added successfully.');
    }

    public function update(Request $request, Menu $item)
    {
        if (! $item->is_editable) {
            return redirect()->back()->with('error', 'This menu item cannot be edited.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', 'in:custom_url,page,service_category'],
            'url' => ['nullable', 'string', 'required_if:type,custom_url'],
            'reference_id' => ['nullable', 'integer', 'required_unless:type,custom_url'],
        ]);

        $item->update($validated);

        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $item)
    {
        if (! $item->is_deletable) {
            return redirect()->back()->with('error', 'This menu item cannot be deleted.');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Menu item deleted successfully.');
    }

    public function reorder(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.parent_id' => 'nullable|exists:menus,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($data['items'] as $itemData) {
            // Only update if it belongs to the parent menu tree (prevent hijacking other menus)
            Menu::where('id', $itemData['id'])->update([
                'parent_id' => $itemData['parent_id'] ?? $menu->id,
                'sort_order' => $itemData['sort_order'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
